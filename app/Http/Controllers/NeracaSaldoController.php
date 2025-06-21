<?php

namespace App\Http\Controllers;

use App\Exports\NeracaSaldoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class NeracaSaldoController extends Controller
{
    public function index(Request $request)
    {
        // Penentuan tanggal berdasarkan filter
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if ($request->filter_type === 'periode') {
            $tanggal_awal = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;
        } elseif ($request->filter_type === 'bulan' && $request->bulan) {
            $tanggal_awal = Carbon::parse($request->bulan)->startOfMonth()->toDateString();
            $tanggal_akhir = Carbon::parse($request->bulan)->endOfMonth()->toDateString();
        }

        // Default ke awal tahun s/d hari ini jika tidak ada filter
        if (!$tanggal_awal || !$tanggal_akhir) {
            $tanggal_awal = Carbon::now()->startOfYear()->toDateString();
            $tanggal_akhir = Carbon::now()->toDateString();
        }

        $data_neraca = $this->ambilDataNeraca($tanggal_awal, $tanggal_akhir);

        return view('neraca_saldo.index', [
            'data_neraca'    => $data_neraca,
            'tanggal_awal'   => $tanggal_awal,
            'tanggal_akhir'  => $tanggal_akhir,
            'filter_type'    => $request->filter_type,
            'bulan'          => $request->bulan,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $tanggal_awal = null;
        $tanggal_akhir = null;

        if ($request->filter_type === 'periode') {
            $tanggal_awal = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;
        } elseif ($request->filter_type === 'bulan' && $request->bulan) {
            $tanggal_awal = Carbon::parse($request->bulan)->startOfMonth()->toDateString();
            $tanggal_akhir = Carbon::parse($request->bulan)->endOfMonth()->toDateString();
        }

        if (!$tanggal_awal || !$tanggal_akhir) {
            $tanggal_awal = Carbon::now()->startOfYear()->toDateString();
            $tanggal_akhir = Carbon::now()->toDateString();
        }

        $data_neraca = $this->ambilDataNeraca($tanggal_awal, $tanggal_akhir);

        return Excel::download(new NeracaSaldoExport($data_neraca), 'neraca_saldo.xlsx');
    }

    protected function ambilDataNeraca($tanggal_awal, $tanggal_akhir)
    {
        $coaList = DB::table('coa')->orderBy('kode_akun')->get();

        $jurnalData = DB::table('jurnal_umum')
            ->select(
                'kode_akun',
                DB::raw("SUM(CASE WHEN tanggal < '{$tanggal_awal}' THEN nominal_debit ELSE 0 END) AS saldo_awal_debit_jurnal"),
                DB::raw("SUM(CASE WHEN tanggal < '{$tanggal_awal}' THEN nominal_kredit ELSE 0 END) AS saldo_awal_kredit_jurnal"),
                DB::raw("SUM(CASE WHEN tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}' THEN nominal_debit ELSE 0 END) AS total_debit"),
                DB::raw("SUM(CASE WHEN tanggal BETWEEN '{$tanggal_awal}' AND '{$tanggal_akhir}' THEN nominal_kredit ELSE 0 END) AS total_kredit")
            )
            ->groupBy('kode_akun')
            ->get()
            ->keyBy('kode_akun');

        $data = [];

        foreach ($coaList as $akun) {
            $jurnal = $jurnalData[$akun->kode_akun] ?? null;

            $saldo_awal_debit = 0;
            $saldo_awal_kredit = 0;

            // Gunakan saldo awal dari COA jika periodenya valid
            if (!empty($akun->periode_saldo_awal) && substr($akun->periode_saldo_awal, 0, 7) <= substr($tanggal_awal, 0, 7)) {
                $saldo_awal_debit += $akun->saldo_awal_debit;
                $saldo_awal_kredit += $akun->saldo_awal_kredit;
            }

            // Tambahkan jurnal sebelum periode
            $saldo_awal_debit += $jurnal->saldo_awal_debit_jurnal ?? 0;
            $saldo_awal_kredit += $jurnal->saldo_awal_kredit_jurnal ?? 0;

            $data[] = (object)[
                'kode_akun'         => $akun->kode_akun,
                'nama_akun'         => $akun->nama_akun,
                'level'             => $akun->level,
                'saldo_awal_debit'  => $saldo_awal_debit,
                'saldo_awal_kredit' => $saldo_awal_kredit,
                'total_debit'       => $jurnal->total_debit ?? 0,
                'total_kredit'      => $jurnal->total_kredit ?? 0,
            ];
        }

        return $data;
    }
}
