<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KartuStok;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KartuStokExport;
use Illuminate\Pagination\LengthAwarePaginator;

class KartuStokController extends Controller
{
    public function index(Request $request)
    {
        $produkList = Produk::all();

        $data = $this->getFilteredData($request);

        return view('kartu_stok.index', [
            'kartuStok' => $data,
            'produkList' => $produkList,
            'request' => $request,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request);
        $tanggalDari = $request->tanggal_dari;
        $tanggalSampai = $request->tanggal_sampai;

        return Excel::download(new KartuStokExport($data, $tanggalDari, $tanggalSampai), 'kartu_stok.xlsx');
    }

    private function getFilteredData(Request $request)
    {
        $tanggalDari = $request->filled('tanggal_dari') ? Carbon::parse($request->tanggal_dari)->format('Y-m-d') : null;
        $tanggalSampai = $request->filled('tanggal_sampai') ? Carbon::parse($request->tanggal_sampai)->format('Y-m-d') : null;

        $query = KartuStok::query()->with('produk');

        if ($tanggalDari && $tanggalSampai) {
            $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('sumber_tujuan')) {
            $query->where('sumber_tujuan', 'like', '%' . $request->sumber_tujuan . '%');
        }

        $transaksi = $query->orderBy('tanggal')->orderBy('id')->get();

        $hasil = [];
        $saldoSementara = [];

        foreach ($transaksi as $trx) {
            $produk = $trx->produk;
            if (!$produk) continue;

            $produkId = $produk->id;

            // Ambil saldo awal hanya 1x untuk produk tersebut
            if (!isset($saldoSementara[$produkId])) {
                $saldo = $produk->saldo_awal_qty ?? 0;

                // Tambah total masuk sebelum tanggal dari trx
                $totalMasuk = KartuStok::where('id_produk', $produkId)
                    ->where('tanggal', '<', $trx->tanggal)
                    ->where('jenis', 'masuk')
                    ->sum('qty');

                $totalKeluar = KartuStok::where('id_produk', $produkId)
                    ->where('tanggal', '<', $trx->tanggal)
                    ->where('jenis', 'keluar')
                    ->sum('qty');

                $saldo += $totalMasuk - $totalKeluar;

                $saldoSementara[$produkId] = $saldo;
            }

            $saldoAwal = $saldoSementara[$produkId];
            $masuk = $trx->jenis === 'masuk' ? $trx->qty : 0;
            $keluar = $trx->jenis === 'keluar' ? $trx->qty : 0;
            $saldoAkhir = $saldoAwal + $masuk - $keluar;

            $saldoSementara[$produkId] = $saldoAkhir;

            $hasil[] = (object)[
                'tanggal' => $trx->tanggal,
                'no_transaksi' => $trx->no_transaksi,
                'produk' => $produk,
                'sumber_tujuan' => $trx->sumber_tujuan,
                'saldo_awal' => $saldoAwal,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'saldo_akhir' => $saldoAkhir,
            ];
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = collect($hasil)->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new LengthAwarePaginator($currentItems, count($hasil), $perPage);
        $paginator->withPath(request()->url());
        $paginator->appends(request()->query());

        return $paginator;
    }
}
