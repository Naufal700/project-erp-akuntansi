<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TransaksiPersediaan;
use App\Exports\LaporanPersediaanExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->endOfMonth()->toDateString();

        // Cek closing
        $closingExists = TransaksiPersediaan::where('jenis', 'saldo_awal')
            ->where('tanggal', $start)
            ->exists();

        $prevMonthEnd = Carbon::parse($start)->subMonth()->endOfMonth()->toDateString();

        // Ambil semua produk
        $allProduk = Produk::with('kategori')->get()->map(function ($produk) use ($start, $end) {
            $saldo_awal = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'saldo_awal')
                ->where('tanggal', $start)
                ->first();

            $produk->saldo_awal_qty = $saldo_awal->qty ?? 0;
            $produk->saldo_awal_harga = $saldo_awal->harga ?? 0;

            $penerimaan = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'penerimaan')
                ->whereBetween('tanggal', [$start, $end]);

            $produk->penerimaan_qty = $penerimaan->sum('qty');
            $produk->penerimaan_harga = $penerimaan->avg('harga') ?? 0;

            $pengeluaran = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'pengeluaran')
                ->whereBetween('tanggal', [$start, $end]);

            $produk->pengeluaran_qty = $pengeluaran->sum('qty');
            $produk->pengeluaran_harga = $pengeluaran->avg('harga') ?? 0;

            $produk->saldo_akhir_qty = ($produk->saldo_awal_qty + $produk->penerimaan_qty) - $produk->pengeluaran_qty;

            $total_qty = $produk->saldo_awal_qty + $produk->penerimaan_qty;
            $total_nilai = ($produk->saldo_awal_qty * $produk->saldo_awal_harga) + ($produk->penerimaan_qty * $produk->penerimaan_harga);
            $produk->saldo_akhir_harga = $total_qty > 0 ? $total_nilai / $total_qty : 0;

            return $produk;
        });

        // Hitung total semua data (bukan hanya per halaman)
        $total = [
            'saldo_awal_qty' => $allProduk->sum('saldo_awal_qty'),
            'saldo_awal_total' => $allProduk->sum(fn($p) => $p->saldo_awal_qty * $p->saldo_awal_harga),

            'penerimaan_qty' => $allProduk->sum('penerimaan_qty'),
            'penerimaan_total' => $allProduk->sum(fn($p) => $p->penerimaan_qty * $p->penerimaan_harga),

            'pengeluaran_qty' => $allProduk->sum('pengeluaran_qty'),
            'pengeluaran_total' => $allProduk->sum(fn($p) => $p->pengeluaran_qty * $p->pengeluaran_harga),

            'saldo_akhir_qty' => $allProduk->sum('saldo_akhir_qty'),
            'saldo_akhir_total' => $allProduk->sum(fn($p) => $p->saldo_akhir_qty * $p->saldo_akhir_harga),
        ];

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 15;
        $paginatedProduk = $allProduk->forPage($page, $perPage);
        $produkList = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedProduk,
            $allProduk->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('laporan.persediaan', compact(
            'produkList',
            'total',
            'closingExists',
            'prevMonthEnd'
        ));
    }
    public function export(Request $request)
    {
        return Excel::download(new LaporanPersediaanExport($request), 'laporan-persediaan.xlsx');
    }

    public function closingByDate(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $closingDate = $request->tanggal;

        // Validasi agar hanya bisa closing pada akhir bulan
        if (date('Y-m-d', strtotime($closingDate)) !== date('Y-m-t', strtotime($closingDate))) {
            return redirect()->back()->with('error', 'Tanggal yang dipilih bukan tanggal akhir bulan.');
        }

        // Tanggal saldo_awal bulan berikutnya (1 bulan ke depan)
        $saldoAwalTanggal = date('Y-m-01', strtotime('first day of next month', strtotime($closingDate)));
        $produkList = Produk::with('kategori')->get();

        foreach ($produkList as $produk) {
            // Hitung total qty masuk (saldo_awal dan penerimaan) sampai tanggal closing
            $penerimaan = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->whereIn('jenis', ['saldo_awal', 'penerimaan'])
                ->where('tanggal', '<=', $closingDate)
                ->sum('qty');

            // Hitung total qty keluar sampai tanggal closing
            $pengeluaran = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'pengeluaran')
                ->where('tanggal', '<=', $closingDate)
                ->sum('qty');

            $saldoQty = $penerimaan - $pengeluaran;

            // Hitung harga rata-rata dari semua saldo masuk (Average Cost)
            $hargaRataRata = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->whereIn('jenis', ['saldo_awal', 'penerimaan'])
                ->where('tanggal', '<=', $closingDate)
                ->avg('harga') ?? ($produk->harga_beli ?? 0);

            // Debugging (cek log Laravel)
            logger()->info("Produk: {$produk->kode_produk}, Penerimaan: $penerimaan, Pengeluaran: $pengeluaran, SaldoQty: $saldoQty, HargaRata: $hargaRataRata");

            // Jika tidak ada saldo, skip produk ini
            if ($saldoQty <= 0) {
                logger()->warning("Lewatkan Produk {$produk->kode_produk} karena saldoQty <= 0");
                continue;
            }

            // Cek apakah saldo_awal untuk bulan berikutnya sudah pernah disimpan
            $sudahAda = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'saldo_awal')
                ->where('tanggal', $saldoAwalTanggal)
                ->exists();

            logger()->info("Cek saldo_awal untuk {$produk->kode_produk} - {$saldoAwalTanggal}: Sudah Ada = " . ($sudahAda ? 'YA' : 'TIDAK'));

            if (!$sudahAda) {
                TransaksiPersediaan::create([
                    'kode_produk' => $produk->kode_produk,
                    'tanggal'     => $saldoAwalTanggal, // 1 bulan berikutnya
                    'jenis'       => 'saldo_awal',
                    'qty'         => $saldoQty,
                    'qty_sisa'    => $saldoQty,
                    'harga'       => $hargaRataRata,
                    'sumber'      => 'Closing manual dari tanggal ' . $closingDate,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Closing berhasil. Saldo awal bulan berikutnya telah dibuat.');
    }
}
