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

        // Cek apakah sudah ada saldo_awal untuk bulan yang dipilih (awal bulan)
        $closingExists = TransaksiPersediaan::where('jenis', 'saldo_awal')
            ->where('tanggal', $start)
            ->exists();

        // Tanggal akhir bulan sebelumnya (untuk default nilai closing manual)
        $prevMonthEnd = Carbon::parse($start)->subMonth()->endOfMonth()->toDateString();

        $produkList = Produk::with('kategori')->get()->map(function ($produk) use ($start, $end) {

            // --- Saldo Awal (Ambil dari saldo_awal di awal bulan saja) ---
            $saldo_awal = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'saldo_awal')
                ->where('tanggal', $start)
                ->first();

            $produk->saldo_awal_qty = $saldo_awal->qty ?? 0;
            $produk->saldo_awal_harga = $saldo_awal->harga ?? 0;

            // --- Penerimaan selama periode ---
            $penerimaan = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'penerimaan')
                ->whereBetween('tanggal', [$start, $end]);

            $produk->penerimaan_qty = $penerimaan->sum('qty');
            $produk->penerimaan_harga = $penerimaan->avg('harga') ?? 0;

            // --- Pengeluaran selama periode ---
            $pengeluaran = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'pengeluaran')
                ->whereBetween('tanggal', [$start, $end]);

            $produk->pengeluaran_qty = $pengeluaran->sum('qty');
            $produk->pengeluaran_harga = $pengeluaran->avg('harga') ?? 0;

            // --- Saldo Akhir (Qty dan Harga Rata-rata terbaru) ---
            $produk->saldo_akhir_qty = ($produk->saldo_awal_qty + $produk->penerimaan_qty) - $produk->pengeluaran_qty;

            // Gunakan rata-rata dari saldo awal dan penerimaan (jika ada) untuk harga akhir
            $total_qty = $produk->saldo_awal_qty + $produk->penerimaan_qty;
            $total_nilai = ($produk->saldo_awal_qty * $produk->saldo_awal_harga) + ($produk->penerimaan_qty * $produk->penerimaan_harga);
            $produk->saldo_akhir_harga = $total_qty > 0 ? $total_nilai / $total_qty : 0;

            return $produk;
        });

        return view('laporan.persediaan', compact(
            'produkList',
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
