<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;
use App\Models\JurnalPenyesuaian;
use App\Exports\NeracaLajurExport;
use Maatwebsite\Excel\Facades\Excel;

class NeracaLajurController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));

        $data = Coa::orderBy('kode_akun')->get()->map(function ($akun) use ($periode) {
            $kode = $akun->kode_akun;

            // Mutasi dari Jurnal Umum
            $mutasiDebit = JurnalUmum::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_debit');

            $mutasiKredit = JurnalUmum::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_kredit');

            // Penyesuaian dari Jurnal Penyesuaian
            $penyesuaianDebit = JurnalPenyesuaian::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_debit');

            $penyesuaianKredit = JurnalPenyesuaian::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_kredit');

            $saldoAwal = $akun->saldo_awal;

            // Neraca Saldo (Saldo Awal + Mutasi)
            $neracaSaldo = ($saldoAwal + $mutasiDebit) - $mutasiKredit;

            // Neraca Setelah Penyesuaian
            $neracaSesudah = $neracaSaldo + $penyesuaianDebit - $penyesuaianKredit;

            return [
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'level' => $akun->level,
                'tipe_akun' => $akun->tipe_akun,

                // Saldo Awal
                'saldo_awal_debit' => $saldoAwal > 0 ? $saldoAwal : 0,
                'saldo_awal_kredit' => $saldoAwal < 0 ? abs($saldoAwal) : 0,

                // Mutasi
                'mutasi_debit' => $mutasiDebit,
                'mutasi_kredit' => $mutasiKredit,

                // Neraca Saldo
                'neraca_saldo_debit' => $neracaSaldo > 0 ? $neracaSaldo : 0,
                'neraca_saldo_kredit' => $neracaSaldo < 0 ? abs($neracaSaldo) : 0,

                // Penyesuaian
                'penyesuaian_debit' => $penyesuaianDebit,
                'penyesuaian_kredit' => $penyesuaianKredit,

                // Neraca Sesudah Penyesuaian (perbaikan penamaan key)
                'neraca_sesudah_debit' => $neracaSesudah > 0 ? $neracaSesudah : 0,
                'neraca_sesudah_kredit' => $neracaSesudah < 0 ? abs($neracaSesudah) : 0,

                // Laba Rugi
                'laba_rugi_debit' => in_array($akun->tipe_akun, ['Beban', 'HPP']) ? $neracaSesudah : 0,
                'laba_rugi_kredit' => in_array($akun->tipe_akun, ['Pendapatan']) ? $neracaSesudah : 0,

                // Neraca Akhir
                'neraca_debit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah > 0 ? $neracaSesudah : 0,
                'neraca_kredit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah < 0 ? abs($neracaSesudah) : 0,
            ];
        });

        return view('neraca_lajur.index', [
            'data' => collect($data),
            'periode' => $periode
        ]);
    }

    private function getPosisi($tipe_akun)
    {
        return match ($tipe_akun) {
            'Kas', 'Bank', 'Piutang', 'Persediaan', 'Aset Tetap', 'Aset' => 'Aktiva',
            'Hutang', 'Kewajiban', 'Modal' => 'Pasiva',
            'Pendapatan' => 'Pendapatan',
            'Beban', 'HPP' => 'Beban',
            default => 'Lainnya',
        };
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $data = $this->getFilteredNeracaLajur($periode);

        return Excel::download(new NeracaLajurExport($data, $periode), 'neraca_lajur_' . $periode . '.xlsx');
    }

    private function getFilteredNeracaLajur($periode)
    {
        // Ambil hanya akun yang pernah dipakai di jurnal umum pada periode tersebut
        $akunDipakai = JurnalUmum::whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
            ->pluck('kode_akun')
            ->unique();

        $data = Coa::whereIn('kode_akun', $akunDipakai)
            ->orderBy('kode_akun')
            ->get()
            ->map(function ($akun) use ($periode) {
                $kode = $akun->kode_akun;

                $mutasiDebit = JurnalUmum::where('kode_akun', $kode)
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                    ->sum('nominal_debit');

                $mutasiKredit = JurnalUmum::where('kode_akun', $kode)
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                    ->sum('nominal_kredit');

                $penyesuaianDebit = JurnalPenyesuaian::where('kode_akun', $kode)
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                    ->sum('nominal_debit');

                $penyesuaianKredit = JurnalPenyesuaian::where('kode_akun', $kode)
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                    ->sum('nominal_kredit');

                $saldoAwal = $akun->saldo_awal;
                $neracaSaldo = ($saldoAwal + $mutasiDebit) - $mutasiKredit;
                $neracaSesudah = $neracaSaldo + $penyesuaianDebit - $penyesuaianKredit;

                return [
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'level' => $akun->level,
                    'tipe_akun' => $akun->tipe_akun,

                    'saldo_awal_debit' => $saldoAwal > 0 ? $saldoAwal : 0,
                    'saldo_awal_kredit' => $saldoAwal < 0 ? abs($saldoAwal) : 0,

                    'mutasi_debit' => $mutasiDebit,
                    'mutasi_kredit' => $mutasiKredit,

                    'neraca_saldo_debit' => $neracaSaldo > 0 ? $neracaSaldo : 0,
                    'neraca_saldo_kredit' => $neracaSaldo < 0 ? abs($neracaSaldo) : 0,

                    'penyesuaian_debit' => $penyesuaianDebit,
                    'penyesuaian_kredit' => $penyesuaianKredit,

                    'neraca_sesudah_debit' => $neracaSesudah > 0 ? $neracaSesudah : 0,
                    'neraca_sesudah_kredit' => $neracaSesudah < 0 ? abs($neracaSesudah) : 0,

                    'laba_rugi_debit' => in_array($akun->tipe_akun, ['Beban', 'HPP']) ? $neracaSesudah : 0,
                    'laba_rugi_kredit' => in_array($akun->tipe_akun, ['Pendapatan']) ? $neracaSesudah : 0,

                    'neraca_debit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah > 0 ? $neracaSesudah : 0,
                    'neraca_kredit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah < 0 ? abs($neracaSesudah) : 0,
                ];
            });

        return collect($data);
    }
}
