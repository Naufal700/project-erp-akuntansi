<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\JurnalUmum;
use App\Models\JurnalPenyesuaian;
use Illuminate\Http\Request;
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
                'laba_rugi_kredit' => in_array($akun->tipe_akun, ['Pendapatan']) ? abs($neracaSesudah) : 0,

                // Neraca Akhir
                'neraca_debit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah > 0 ? $neracaSesudah : 0,
                'neraca_kredit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah < 0 ? abs($neracaSesudah) : 0,
            ];
        });
        $periode = $request->input('periode', date('Y-m'));
        $data = $this->getFilteredNeracaLajur($periode);

        return view('neraca_lajur.index', [
            'data' => collect($data),
            'periode' => $periode
        ]);
    }
    public function export(Request $request)
    {
        $periode = $request->get('periode', date('Y-m'));
        $data = $this->getFilteredNeracaLajur($periode);

        return Excel::download(new NeracaLajurExport($data, $periode), 'neraca_lajur_' . $periode . '.xlsx');
    }

    private function getFilteredNeracaLajur($periode)
    {
        return Coa::orderBy('kode_akun')->get()->map(function ($akun) use ($periode) {
            $kode = $akun->kode_akun;

            // Cek saldo awal per periode (jika kamu simpan per periode, sesuaikan)
            $saldo_awal_debit = $akun->periode_saldo_awal === $periode ? (float) $akun->saldo_awal_debit : 0;
            $saldo_awal_kredit = $akun->periode_saldo_awal === $periode ? (float) $akun->saldo_awal_kredit : 0;
            $saldoAwal = $saldo_awal_debit - $saldo_awal_kredit;

            // Mutasi
            $mutasiDebit = (float) JurnalUmum::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_debit');

            $mutasiKredit = (float) JurnalUmum::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_kredit');

            // Penyesuaian
            $penyesuaianDebit = (float) JurnalPenyesuaian::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_debit');

            $penyesuaianKredit = (float) JurnalPenyesuaian::where('kode_akun', $kode)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
                ->sum('nominal_kredit');

            // Filter akun yang tidak punya data sama sekali
            if (
                $saldo_awal_debit == 0 &&
                $saldo_awal_kredit == 0 &&
                $mutasiDebit == 0 &&
                $mutasiKredit == 0 &&
                $penyesuaianDebit == 0 &&
                $penyesuaianKredit == 0
            ) {
                return null; // Akan difilter setelah map
            }

            $neracaSaldo = $saldoAwal + $mutasiDebit - $mutasiKredit;
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
                'laba_rugi_kredit' => in_array($akun->tipe_akun, ['Pendapatan']) ? abs($neracaSesudah) : 0,

                'neraca_debit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah > 0 ? $neracaSesudah : 0,
                'neraca_kredit' => !in_array($akun->tipe_akun, ['Pendapatan', 'Beban', 'HPP']) && $neracaSesudah < 0 ? abs($neracaSesudah) : 0,
            ];
        })->filter()->values();
    }
}
