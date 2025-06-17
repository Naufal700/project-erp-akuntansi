<?php

namespace App\Http\Controllers;

use App\Models\KartuStok;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengirimanPenjualan;
use App\Models\TransaksiPersediaan;

class PengirimanPenjualanController extends Controller
{
    private function generateNomorSuratJalan()
    {
        $date = date('Ymd');
        $last = PengirimanPenjualan::whereDate('created_at', date('Y-m-d'))
            ->orderBy('id', 'desc')->first();
        $number = $last ? (int) substr($last->nomor_surat_jalan, -3) + 1 : 1;
        return 'SJ-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $pengiriman = PengirimanPenjualan::with(['salesOrder.pelanggan'])->latest()->get();
        return view('pengiriman_penjualan.index', compact('pengiriman'));
    }

    public function create()
    {
        $salesOrder = SalesOrder::all();
        $nomorSuratJalan = $this->generateNomorSuratJalan();
        return view('pengiriman_penjualan.create', compact('salesOrder', 'nomorSuratJalan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_so' => 'required|exists:sales_order,id'
        ]);

        DB::beginTransaction();

        try {
            $nomorSuratJalan = $this->generateNomorSuratJalan();
            $pengiriman = PengirimanPenjualan::create([
                'nomor_surat_jalan' => $nomorSuratJalan,
                'tanggal' => $request->tanggal,
                'id_so' => $request->id_so,
                'status_pengiriman' => 'dikirim',
            ]);

            $salesOrder = SalesOrder::with('salesOrderDetail.produk')->findOrFail($request->id_so);

            foreach ($salesOrder->salesOrderDetail as $detail) {
                $produk = $detail->produk;

                if ($produk->stok < $detail->qty) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Stok produk ' . $produk->nama . ' tidak mencukupi.');
                }

                $produk->stok -= $detail->qty;
                $produk->save();

                $hargaFIFO = $this->ambilHargaFIFO($produk->kode_produk, $detail->qty);

                TransaksiPersediaan::create([
                    'tanggal' => $request->tanggal,
                    'kode_produk' => $produk->kode_produk,
                    'jenis' => 'pengeluaran',
                    'sumber' => 'Pengiriman SO#' . $salesOrder->nomor_so,
                    'id_ref' => $pengiriman->id,
                    'qty' => $detail->qty,
                    'harga' => $hargaFIFO,
                    'qty_sisa' => 0,
                ]);

                // Catat ke kartu stok
                KartuStok::create([
                    'tanggal' => $request->tanggal,
                    'no_transaksi' => $nomorSuratJalan,
                    'id_produk' => $produk->id,
                    'jenis' => 'keluar',
                    'sumber_tujuan' => $salesOrder->customer->nama ?? 'Customer',
                    'qty' => $detail->qty,
                ]);
            }


            DB::commit();
            return redirect()->route('pengiriman-penjualan.index')
                ->with('success', 'Pengiriman berhasil. Stok dikurangi & tercatat di persediaan. Nomor SJ: ' . $nomorSuratJalan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pengiriman = PengirimanPenjualan::findOrFail($id);
        $salesOrder = SalesOrder::all();
        return view('pengiriman_penjualan.edit', compact('pengiriman', 'salesOrder'));
    }

    public function update(Request $request, $id)
    {
        $pengiriman = PengirimanPenjualan::findOrFail($id);

        $request->validate([
            'nomor_surat_jalan' => 'required|unique:pengiriman_penjualan,nomor_surat_jalan,' . $pengiriman->id,
            'tanggal' => 'required|date',
            'id_so' => 'required|exists:sales_order,id',
            'status_pengiriman' => 'required|in:dikirim,diterima,dibatalkan'
        ]);

        $pengiriman->update($request->all());

        return redirect()->route('pengiriman-penjualan.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pengiriman = PengirimanPenjualan::with('salesOrder.salesOrderDetail.produk')->findOrFail($id);

            foreach ($pengiriman->salesOrder->salesOrderDetail as $detail) {
                $produk = $detail->produk;

                // Kembalikan stok
                $produk->stok += $detail->qty;
                $produk->save();

                // Hapus dari transaksi persediaan
                TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                    ->where('id_ref', $pengiriman->id)
                    ->where('jenis', 'pengeluaran')
                    ->where('sumber', 'like', '%SO#' . $pengiriman->salesOrder->nomor_so . '%')
                    ->delete();

                // Hapus dari kartu stok
                KartuStok::where('id_produk', $produk->id)
                    ->where('no_transaksi', $pengiriman->nomor_surat_jalan)
                    ->where('jenis', 'keluar')
                    ->where('sumber_tujuan', $pengiriman->salesOrder->customer->nama ?? 'Customer')
                    ->delete();
            }

            $pengiriman->delete();

            DB::commit();
            return redirect()->route('pengiriman-penjualan.index')->with('success', 'Pengiriman dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakSuratJalan($id)
    {
        $pengiriman = PengirimanPenjualan::with('salesOrder')->findOrFail($id);
        $pdf = \PDF::loadView('pengiriman_penjualan.surat_jalan_pdf', compact('pengiriman'));
        return $pdf->download('SuratJalan_' . $pengiriman->nomor_surat_jalan . '.pdf');
    }

    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:dikirim,diterima,dibatalkan'
        ]);

        $pengiriman = PengirimanPenjualan::findOrFail($id);
        $pengiriman->status_pengiriman = $request->status_pengiriman;
        $pengiriman->save();

        return redirect()->route('pengiriman-penjualan.index')->with('success', 'Status pengiriman berhasil diubah.');
    }

    public function show($id)
    {
        $pengiriman = PengirimanPenjualan::with('salesOrder.salesOrderDetail.produk', 'salesOrder.customer')->findOrFail($id);
        return view('pengiriman_penjualan.show', compact('pengiriman'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:draft,dikirim,diterima',
        ]);

        $pengiriman = PengirimanPenjualan::findOrFail($id);
        $pengiriman->status_pengiriman = $request->status_pengiriman;
        $pengiriman->save();

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    private function ambilHargaFIFO($kode_produk, $qty)
    {
        $persediaanMasuk = \App\Models\TransaksiPersediaan::where('kode_produk', $kode_produk)
            ->where('jenis', 'penerimaan')
            ->where('qty_sisa', '>', 0)
            ->orderBy('tanggal')
            ->get();

        $totalQty = 0;
        $totalHarga = 0;

        foreach ($persediaanMasuk as $masuk) {
            $ambilQty = min($masuk->qty_sisa, $qty - $totalQty);

            $totalQty += $ambilQty;
            $totalHarga += $ambilQty * $masuk->harga;

            $masuk->qty_sisa -= $ambilQty;
            $masuk->save();

            if ($totalQty >= $qty) {
                break;
            }
        }

        if ($totalQty < $qty || $totalQty == 0) {
            return \App\Models\TransaksiPersediaan::where('kode_produk', $kode_produk)
                ->where('jenis', 'penerimaan')
                ->avg('harga') ?? 0;
        }

        return $totalHarga / $totalQty;
    }
}
