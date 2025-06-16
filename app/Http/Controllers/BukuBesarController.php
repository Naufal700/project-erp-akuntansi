<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuBesarExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal', date('Y-m-01'));
        $tanggal_akhir = $request->input('tanggal_akhir', date('Y-m-t'));
        $filter_akun = $request->input('akun');

        $all_coa = DB::table('coa')->orderBy('kode_akun')->get();

        $query = DB::table('jurnal_umum')->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);

        if ($filter_akun) {
            $query->where('kode_akun', $filter_akun);
            $kodeAkunTransaksi = collect([$filter_akun]);
        } else {
            $kodeAkunTransaksi = $query->distinct()->pluck('kode_akun');
        }

        // Pagination manual untuk koleksi akun
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentPageItems = $kodeAkunTransaksi->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $data = [];

        foreach ($currentPageItems as $kode_akun) {
            $coa = DB::table('coa')->where('kode_akun', $kode_akun)->first();
            if (!$coa) continue;

            $saldoSebelum = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->where('tanggal', '<', $tanggal_awal)
                ->selectRaw('
                    COALESCE(SUM(nominal_debit),0) as total_debit,
                    COALESCE(SUM(nominal_kredit),0) as total_kredit
                ')->first();

            $saldo_awal = $coa->saldo_awal + ($saldoSebelum->total_debit - $saldoSebelum->total_kredit);

            $jurnal = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('tanggal')
                ->get();

            $total_debit = $jurnal->sum('nominal_debit');
            $total_kredit = $jurnal->sum('nominal_kredit');
            $saldo_akhir = $saldo_awal + $total_debit - $total_kredit;

            $data[] = [
                'coa' => $coa,
                'saldo_awal' => $saldo_awal,
                'jurnal' => $jurnal,
                'total_debit' => $total_debit,
                'total_kredit' => $total_kredit,
                'saldo_akhir' => $saldo_akhir,
            ];
        }

        $paginatedData = new LengthAwarePaginator(
            $data,
            $kodeAkunTransaksi->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('buku_besar.index', [
            'data' => $paginatedData, // <- ganti dari $data ke $paginatedData
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'all_coa' => $all_coa,
        ]);
    }

    protected function prepareDataForExport($filters)
    {
        $tanggal_awal = $filters['tanggal_awal'] ?? date('Y-m-01');
        $tanggal_akhir = $filters['tanggal_akhir'] ?? date('Y-m-t');
        $filter_akun = $filters['akun'] ?? null;

        $query = DB::table('jurnal_umum')->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);

        if ($filter_akun) {
            $query->where('kode_akun', $filter_akun);
            $kodeAkunTransaksi = collect([$filter_akun]);
        } else {
            $kodeAkunTransaksi = $query->distinct()->pluck('kode_akun');
        }

        $data = [];

        foreach ($kodeAkunTransaksi as $kode_akun) {
            $coa = DB::table('coa')->where('kode_akun', $kode_akun)->first();
            if (!$coa) continue;

            $saldoSebelum = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->where('tanggal', '<', $tanggal_awal)
                ->selectRaw('
                    COALESCE(SUM(nominal_debit),0) as total_debit,
                    COALESCE(SUM(nominal_kredit),0) as total_kredit
                ')->first();

            $saldo_awal = $coa->saldo_awal + ($saldoSebelum->total_debit - $saldoSebelum->total_kredit);

            $jurnal = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('tanggal')
                ->get();

            $total_debit = $jurnal->sum('nominal_debit');
            $total_kredit = $jurnal->sum('nominal_kredit');
            $saldo_akhir = $saldo_awal + $total_debit - $total_kredit;

            $data[] = [
                'coa' => $coa,
                'saldo_awal' => $saldo_awal,
                'jurnal' => $jurnal,
                'total_debit' => $total_debit,
                'total_kredit' => $total_kredit,
                'saldo_akhir' => $saldo_akhir,
            ];
        }

        return $data;
    }

    public function exportExcel(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $filter_akun = $request->kode_akun; // atau 'filter_akun' tergantung nama field request-nya

        return Excel::download(
            new BukuBesarExport($tanggal_awal, $tanggal_akhir, $filter_akun),
            'buku_besar.xlsx'
        );
    }

    public function exportPDF(Request $request)
    {
        $data = $this->prepareDataForExport($request->all());

        $pdf = Pdf::loadView('buku_besar.export_pdf', compact('data'));
        return $pdf->download('buku_besar.pdf');
    }
    private function getDataBukuBesar(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $kode_akun = $request->kode_akun;

        $data = DB::table('jurnal_umum')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->select('jurnal_umum.*', 'coa.nama_akun')
            ->where('jurnal_umum.kode_akun', $kode_akun)
            ->whereBetween('jurnal_umum.tanggal', [$tanggal_awal, $tanggal_akhir])
            ->orderBy('jurnal_umum.tanggal')
            ->get();

        return $data;
    }
}
