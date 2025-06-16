<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\NeracaSaldoExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class NeracaSaldoController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if ($request->filter_type == 'periode') {
            $tanggal_awal = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;
        } elseif ($request->filter_type == 'bulan') {
            if ($request->bulan) {
                $tanggal_awal = Carbon::parse($request->bulan)->startOfMonth()->toDateString();
                $tanggal_akhir = Carbon::parse($request->bulan)->endOfMonth()->toDateString();
            }
        }

        if (!$tanggal_awal || !$tanggal_akhir) {
            $tanggal_awal = Carbon::now()->startOfYear()->toDateString();
            $tanggal_akhir = Carbon::now()->toDateString();
        }

        $data_neraca = $this->ambilDataNeraca($tanggal_awal, $tanggal_akhir);

        return view('neraca_saldo.index', [
            'data_neraca' => $data_neraca,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'filter_type' => $request->filter_type,
            'bulan' => $request->bulan,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if ($request->filter_type == 'periode') {
            $tanggal_awal = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;
        } elseif ($request->filter_type == 'bulan') {
            if ($request->bulan) {
                $tanggal_awal = Carbon::parse($request->bulan)->startOfMonth()->toDateString();
                $tanggal_akhir = Carbon::parse($request->bulan)->endOfMonth()->toDateString();
            }
        }

        if (!$tanggal_awal || !$tanggal_akhir) {
            $tanggal_awal = Carbon::now()->startOfYear()->toDateString();
            $tanggal_akhir = Carbon::now()->toDateString();
        }

        $data_neraca = $this->ambilDataNeraca($tanggal_awal, $tanggal_akhir);

        return Excel::download(new NeracaSaldoExport($data_neraca), 'neraca_saldo.xlsx');
    }

    // Refactor method ambilDataNeraca untuk menerima tanggal awal dan akhir
    protected function ambilDataNeraca($tanggal_awal, $tanggal_akhir)
    {
        $data_neraca = DB::table('coa as c')
            ->join(DB::raw("(
            SELECT kode_akun,
                   SUM(CASE WHEN tanggal < '{$tanggal_awal}' THEN nominal_debit ELSE 0 END) as saldo_awal_debit,
                   SUM(CASE WHEN tanggal < '{$tanggal_awal}' THEN nominal_kredit ELSE 0 END) as saldo_awal_kredit,
                   SUM(CASE WHEN tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}' THEN nominal_debit ELSE 0 END) as total_debit,
                   SUM(CASE WHEN tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}' THEN nominal_kredit ELSE 0 END) as total_kredit
            FROM jurnal_umum
            GROUP BY kode_akun
        ) as ju"), 'c.kode_akun', '=', 'ju.kode_akun')
            ->select(
                'c.kode_akun',
                'c.nama_akun',
                'c.level',
                'c.saldo_awal',
                DB::raw('COALESCE(ju.saldo_awal_debit,0) as saldo_awal_debit'),
                DB::raw('COALESCE(ju.saldo_awal_kredit,0) as saldo_awal_kredit'),
                DB::raw('COALESCE(ju.total_debit,0) as total_debit'),
                DB::raw('COALESCE(ju.total_kredit,0) as total_kredit')
            )
            ->orderBy('c.kode_akun')
            ->get();

        foreach ($data_neraca as $akun) {
            $akun->saldo_awal = ($akun->saldo_awal ?? 0) + $akun->saldo_awal_debit - $akun->saldo_awal_kredit;
        }

        return $data_neraca;
    }
}
