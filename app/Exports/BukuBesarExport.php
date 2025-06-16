<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class BukuBesarExport implements FromView
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $filter_akun;

    public function __construct($tanggal_awal, $tanggal_akhir, $filter_akun)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->filter_akun = $filter_akun;
    }

    public function view(): View
    {
        $query = DB::table('jurnal_umum')
            ->whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir]);

        if ($this->filter_akun) {
            $query->where('kode_akun', $this->filter_akun);
            $kodeAkunTransaksi = collect([$this->filter_akun]);
        } else {
            $kodeAkunTransaksi = $query->distinct()->pluck('kode_akun');
        }

        $data = [];

        foreach ($kodeAkunTransaksi as $kode_akun) {
            $coa = DB::table('coa')->where('kode_akun', $kode_akun)->first();
            if (!$coa) continue;

            $saldoSebelum = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->where('tanggal', '<', $this->tanggal_awal)
                ->selectRaw('
                    COALESCE(SUM(nominal_debit),0) as total_debit,
                    COALESCE(SUM(nominal_kredit),0) as total_kredit
                ')->first();

            $saldo_awal = $coa->saldo_awal + ($saldoSebelum->total_debit - $saldoSebelum->total_kredit);

            $jurnal = DB::table('jurnal_umum')
                ->where('kode_akun', $kode_akun)
                ->whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
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

        return view('exports.buku_besar', [
            'data' => $data,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
        ]);
    }
}
