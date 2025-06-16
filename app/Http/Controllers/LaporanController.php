<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\NeracaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function labaRugi(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $request->input('tanggal_akhir') ?? date('Y-m-d');

        $akunPendapatan = DB::table('coa')->where('tipe_akun', 'Pendapatan')->pluck('kode_akun');
        $akunHPP       = DB::table('coa')->where('tipe_akun', 'HPP')->pluck('kode_akun');
        $akunBeban     = DB::table('coa')->where('tipe_akun', 'Beban')->pluck('kode_akun');

        $pendapatan = DB::table('jurnal_umum')
            ->whereIn('kode_akun', $akunPendapatan)
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum('nominal_kredit');

        $hpp = DB::table('jurnal_umum')
            ->whereIn('kode_akun', $akunHPP)
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum('nominal_debit');

        $beban = DB::table('jurnal_umum')
            ->whereIn('kode_akun', $akunBeban)
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum('nominal_debit');

        $laba_kotor = $pendapatan - $hpp;
        $laba_bersih = $laba_kotor - $beban;

        return view('laporan.laba_rugi', compact(
            'tanggal_awal',
            'tanggal_akhir',
            'pendapatan',
            'hpp',
            'beban',
            'laba_kotor',
            'laba_bersih'
        ));
    }

    public function neraca(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');

        // Ambil akun-akun per kategori
        $aset_lancar = DB::table('coa')->whereIn('tipe_akun', ['Kas', 'Bank', 'Piutang', 'Persediaan'])->get();
        $aset_tetap = DB::table('coa')->where('tipe_akun', 'Aset Tetap')->get();
        $kewajiban_jp = DB::table('coa')->where('tipe_akun', 'Kewajiban')->get();
        $kewajiban_pj = DB::table('coa')->where('tipe_akun', 'Hutang')->get();
        $modal = DB::table('coa')->where('tipe_akun', 'Modal')->get();

        // Hitung Laba Ditahan (s/d akhir tahun sebelumnya)
        $tahun_lalu = date('Y', strtotime($tanggal)) - 1;
        $tanggal_akhir_tahun_lalu = $tahun_lalu . '-12-31';

        $pendapatan_lalu = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->where('coa.tipe_akun', 'Pendapatan')
            ->whereDate('jurnal_umum.tanggal', '<=', $tanggal_akhir_tahun_lalu)
            ->sum('jurnal_umum.nominal_kredit');

        $biaya_lalu = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->where('coa.tipe_akun', 'Beban')
            ->whereDate('jurnal_umum.tanggal', '<=', $tanggal_akhir_tahun_lalu)
            ->sum('jurnal_umum.nominal_debit');

        $laba_ditahan = $pendapatan_lalu - $biaya_lalu;

        // Hitung Laba Berjalan (1 Jan tahun ini sampai tanggal yang dipilih)
        $awal_tahun_ini = date('Y', strtotime($tanggal)) . '-01-01';

        $pendapatan_berjalan = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->where('coa.tipe_akun', 'Pendapatan')
            ->whereBetween('jurnal_umum.tanggal', [$awal_tahun_ini, $tanggal])
            ->sum('jurnal_umum.nominal_kredit');

        $biaya_berjalan = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->where('coa.tipe_akun', 'Beban')
            ->whereBetween('jurnal_umum.tanggal', [$awal_tahun_ini, $tanggal])
            ->sum('jurnal_umum.nominal_debit');

        $laba_berjalan = $pendapatan_berjalan - $biaya_berjalan;

        // Ambil semua akun yang dibutuhkan
        $daftar_akun = $aset_lancar
            ->merge($aset_tetap)
            ->merge($kewajiban_jp)
            ->merge($kewajiban_pj)
            ->merge($modal);

        $saldo_jurnal = DB::table('jurnal_umum')
            ->select(
                'kode_akun',
                DB::raw('SUM(nominal_debit) as total_debit'),
                DB::raw('SUM(nominal_kredit) as total_kredit')
            )
            ->whereIn('kode_akun', $daftar_akun->pluck('kode_akun'))
            ->whereDate('tanggal', '<=', $tanggal)
            ->groupBy('kode_akun')
            ->get()
            ->keyBy('kode_akun');

        // Hitung saldo tiap kelompok
        $data = [
            'tanggal' => $tanggal,
            'aset_lancar' => $this->hitungSaldo($aset_lancar, $saldo_jurnal),
            'aset_tetap' => $this->hitungSaldo($aset_tetap, $saldo_jurnal),
            'kewajiban_jp' => $this->hitungSaldo($kewajiban_jp, $saldo_jurnal),
            'kewajiban_pj' => $this->hitungSaldo($kewajiban_pj, $saldo_jurnal),
            'modal' => $this->hitungSaldo($modal, $saldo_jurnal),
            'laba_ditahan' => $laba_ditahan,
            'laba_berjalan' => $laba_berjalan,
        ];
        $total_aset_lancar = collect($data['aset_lancar'])->sum('saldo');
        $total_aset_tetap = collect($data['aset_tetap'])->sum('saldo');
        $total_aset = $total_aset_lancar + $total_aset_tetap;

        $total_kewajiban_jp = collect($data['kewajiban_jp'])->sum('saldo');
        $total_kewajiban_pj = collect($data['kewajiban_pj'])->sum('saldo');
        $total_kewajiban = $total_kewajiban_jp + $total_kewajiban_pj;

        $total_modal = collect($data['modal'])->sum('saldo') + $data['laba_ditahan'] + $data['laba_berjalan'];
        $total_pasiva = $total_kewajiban + $total_modal;
        $data['total_aset'] = $total_aset;
        $data['total_kewajiban'] = $total_kewajiban;
        $data['total_modal'] = $total_modal;
        $data['total_passiva'] = $total_pasiva;

        return view('laporan.neraca', $data);
    }

    private function hitungSaldo($akunList, $saldo_jurnal)
    {
        $result = [];

        foreach ($akunList as $akun) {
            $jurnal = $saldo_jurnal[$akun->kode_akun] ?? (object)['total_debit' => 0, 'total_kredit' => 0];

            // Asumsikan posisi normal: 'debit' untuk aset/beban, 'kredit' untuk kewajiban/modal/pendapatan
            $posisi_normal = $this->getPosisiNormal($akun->tipe_akun);

            $saldo = $posisi_normal === 'debit'
                ? $akun->saldo_awal + ($jurnal->total_debit - $jurnal->total_kredit)
                : $akun->saldo_awal + ($jurnal->total_kredit - $jurnal->total_debit);

            $result[] = [
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'saldo' => $saldo,
            ];
        }

        return $result;
    }

    private function getPosisiNormal($tipe_akun)
    {
        $debit = ['Kas', 'Bank', 'Piutang', 'Persediaan', 'Aset Tetap', 'Beban'];
        return in_array($tipe_akun, $debit) ? 'debit' : 'kredit';
    }
    // Untuk export Excel
    public function export(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');
        return Excel::download(new NeracaExport($tanggal), 'Laporan-Neraca-' . $tanggal . '.xlsx');
    }

    // Untuk closing saldo akhir jadi saldo awal bulan berikutnya
    public function closing(Request $request)
    {
        $tanggal = $request->input('tanggal');

        // Logika ambil semua akun neraca, hitung saldo akhir, lalu simpan ke tabel saldo_awal untuk bulan berikutnya.

        return redirect()->back()->with('success', 'Closing berhasil dilakukan.');
    }

    public function arusKasLangsung(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? now()->startOfMonth()->toDateString();
        $tanggal_akhir = $request->input('tanggal_akhir') ?? now()->toDateString();

        $akunKas = DB::table('coa')
            ->whereIn('tipe_akun', ['Kas', 'Bank'])
            ->pluck('kode_akun')
            ->toArray();

        $jurnalKas = DB::table('jurnal_umum')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->whereIn('kode_akun', $akunKas)
            ->where(function ($q) {
                $q->where('nominal_debit', '>', 0)
                    ->orWhere('nominal_kredit', '>', 0);
            })
            ->orderBy('tanggal')
            ->get();

        $mapping = DB::table('mapping_jurnal')
            ->get()
            ->keyBy(fn($m) => strtolower(trim($m->modul ?? '') . '|' . trim($m->event ?? '')));

        $arusKas = [
            'operasi' => [],
            'investasi' => [],
            'pendanaan' => [],
        ];

        foreach ($jurnalKas as $row) {
            $modul = strtolower(trim($row->modul ?? ''));
            $event = strtolower(trim($row->event ?? ''));
            $key = $modul . '|' . $event;

            $kelompok = 'operasi';
            $jenis = $row->nominal_debit > 0 ? 'masuk' : 'keluar';
            $jumlah = abs($row->nominal_debit - $row->nominal_kredit);
            $keterangan = $row->keterangan;

            if ($mapping->has($key)) {
                $map = $mapping[$key];
                $kelompok = $map->arus_kas_kelompok ?? 'operasi';
                $jenis = $map->arus_kas_jenis ?? $jenis;
                $keterangan = $map->arus_kas_keterangan ?? $row->keterangan;
            }

            $arusKas[$kelompok][] = [
                'tanggal' => $row->tanggal,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
                'jenis' => $jenis,
            ];
        }

        $totalArusKas = [
            'operasi' => [
                'masuk' => collect($arusKas['operasi'])->where('jenis', 'masuk')->sum('jumlah'),
                'keluar' => collect($arusKas['operasi'])->where('jenis', 'keluar')->sum('jumlah'),
            ],
            'investasi' => [
                'masuk' => collect($arusKas['investasi'])->where('jenis', 'masuk')->sum('jumlah'),
                'keluar' => collect($arusKas['investasi'])->where('jenis', 'keluar')->sum('jumlah'),
            ],
            'pendanaan' => [
                'masuk' => collect($arusKas['pendanaan'])->where('jenis', 'masuk')->sum('jumlah'),
                'keluar' => collect($arusKas['pendanaan'])->where('jenis', 'keluar')->sum('jumlah'),
            ],
        ];

        return view('laporan.arus_kas', compact('arusKas', 'tanggal_awal', 'tanggal_akhir', 'totalArusKas'));
    }

    public function perubahanModal(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal') ?? now()->startOfYear()->toDateString();
        $tanggal_akhir = $request->input('tanggal_akhir') ?? now()->toDateString();

        $modalAwal = DB::table('coa')
            ->where('tipe_akun', 'Modal')
            ->sum('saldo_awal');

        $setoranModal = DB::table('jurnal_umum')
            ->join('coa', 'coa.kode_akun', '=', 'jurnal_umum.kode_akun')
            ->where('coa.tipe_akun', 'Modal')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum(DB::raw('nominal_kredit - nominal_debit'));

        $prive = DB::table('jurnal_umum')
            ->where('keterangan', 'like', '%prive%')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum(DB::raw('nominal_debit - nominal_kredit'));

        $pendapatan = DB::table('jurnal_umum')
            ->join('coa', 'coa.kode_akun', '=', 'jurnal_umum.kode_akun')
            ->where('coa.tipe_akun', 'Pendapatan')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum(DB::raw('nominal_kredit - nominal_debit'));

        $beban = DB::table('jurnal_umum')
            ->join('coa', 'coa.kode_akun', '=', 'jurnal_umum.kode_akun')
            ->where('coa.tipe_akun', 'Beban')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->sum(DB::raw('nominal_debit - nominal_kredit'));

        $labaBersih = $pendapatan - $beban;

        $modalAkhir = $modalAwal + $setoranModal + $labaBersih - $prive;

        return view('laporan.perubahan_modal', compact(
            'modalAwal',
            'setoranModal',
            'labaBersih',
            'prive',
            'modalAkhir',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }
}
