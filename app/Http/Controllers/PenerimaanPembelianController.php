<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KartuStok;
use App\Models\JurnalUmum;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MappingJurnal;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PenerimaanPembelian;
use App\Models\PurchaseOrderDetail;
use App\Models\TransaksiPersediaan;
use App\Models\PenerimaanPembelianDetail;

class PenerimaanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = PenerimaanPembelian::with(['purchaseOrder.supplier', 'detail'])->latest();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_penerimaan', 'like', '%' . $keyword . '%')
                    ->orWhereHas('purchaseOrder', function ($s) use ($keyword) {
                        $s->where('nomor_po', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $penerimaan = $query->paginate(10);

        return view('penerimaan.index', compact('penerimaan'));
    }

    public function create()
    {
        $poList = PurchaseOrder::with('supplier')->where('status', '!=', 'diterima')->get();
        $produkList = Produk::all();

        return view('penerimaan.create', compact('poList', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_po' => 'required|exists:purchase_order,id',
            'id_produk' => 'required|array',
            'id_produk.*' => 'required|exists:produk,id',
            'qty_diterima' => 'required|array',
            'qty_diterima.*' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $nomor = 'PNR-' . strtoupper(Str::random(6));

            $penerimaan = PenerimaanPembelian::create([
                'nomor_penerimaan' => $nomor,
                'tanggal' => $request->tanggal,
                'id_po' => $request->id_po,
                'status' => 'belum_faktur',
            ]);

            // Ambil data PO detail berdasarkan id_po
            $poDetails = PurchaseOrderDetail::where('id_po', $request->id_po)->get()->keyBy('id_produk');

            foreach ($request->id_produk as $i => $id_produk) {
                $qty_diterima = $request->qty_diterima[$i];

                // Simpan ke detail penerimaan
                PenerimaanPembelianDetail::create([
                    'id_penerimaan' => $penerimaan->id,
                    'id_produk' => $id_produk,
                    'qty_diterima' => $qty_diterima,
                ]);

                // Update stok produk
                $produk = Produk::find($id_produk);
                if ($produk) {
                    $produk->stok += $qty_diterima;
                    $produk->save();
                }

                // Ambil harga beli dari PO detail
                $harga = $poDetails[$id_produk]->harga ?? 0;

                // Simpan ke transaksi persediaan
                TransaksiPersediaan::create([
                    'kode_produk' => $produk->kode_produk,
                    'tanggal' => $request->tanggal,
                    'jenis' => 'penerimaan',
                    'sumber' => 'Penerimaan PO#' . $penerimaan->purchaseOrder->nomor_po,
                    'id_ref' => $penerimaan->id,
                    'qty' => $qty_diterima,
                    'harga' => $harga,
                    'qty_sisa' => $qty_diterima,
                ]);

                // Simpan ke kartu stok
                KartuStok::create([
                    'tanggal' => $request->tanggal,
                    'no_transaksi' => $nomor,
                    'id_produk' => $id_produk,
                    'jenis' => 'masuk',
                    'sumber_tujuan' => $penerimaan->purchaseOrder->supplier->nama ?? 'Supplier',
                    'qty' => $qty_diterima,
                ]);
            }
            // Ambil mapping jurnal untuk penerimaan pembelian
            $mapping = MappingJurnal::where('modul', 'pembelian')->where('event', 'penerimaan barang')->first();

            if ($mapping) {
                $total = $harga * $qty_diterima;

                // Jurnal DEBIT ke Persediaan
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'kode_akun' => $mapping->kode_akun_debit,
                    'nominal_debit' => $total,
                    'nominal_kredit' => 0,
                    'keterangan' => 'Penerimaan dari PO #' . $penerimaan->purchaseOrder->nomor_po,
                    'ref' => 'goods_receipt',
                    'ref_id' => $penerimaan->id,
                    'modul' => 'penerimaan_pembelian',
                ]);

                // Jurnal KREDIT ke Hutang Dagang
                JurnalUmum::create([
                    'tanggal' => $request->tanggal,
                    'kode_akun' => $mapping->kode_akun_kredit,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $total,
                    'keterangan' => 'Penerimaan dari PO #' . $penerimaan->purchaseOrder->nomor_po,
                    'ref' => 'goods_receipt',
                    'ref_id' => $penerimaan->id,
                    'modul' => 'penerimaan_pembelian',
                ]);
            }
            // Update status PO jadi diterima
            PurchaseOrder::where('id', $request->id_po)->update(['status' => 'diterima']);

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penerimaan = PenerimaanPembelian::with('purchaseOrder')->findOrFail($id);
        return view('penerimaan.show', compact('penerimaan'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $penerimaan = PenerimaanPembelian::with('detail')->findOrFail($id);

            // Update status PO ke draft
            $po = $penerimaan->purchaseOrder;
            if ($po) {
                $po->status = 'draft';
                $po->save();
            }

            // Kembalikan stok & hapus transaksi persediaan
            foreach ($penerimaan->detail as $detail) {
                $produk = Produk::find($detail->id_produk);
                if ($produk) {
                    $produk->stok -= $detail->qty_diterima;
                    $produk->save();

                    // Hapus transaksi masuk dari persediaan
                    TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                        ->where('tanggal', $penerimaan->tanggal)
                        ->where('jenis', 'penerimaan')
                        ->where('id_ref', $penerimaan->id)
                        ->delete();

                    // Hapus dari kartu stok
                    KartuStok::where('id_produk', $produk->id)
                        ->where('no_transaksi', $penerimaan->nomor_penerimaan)
                        ->where('jenis', 'masuk')
                        ->where('sumber_tujuan', $penerimaan->purchaseOrder->supplier->nama ?? 'Supplier')
                        ->delete();
                }
            }

            // Hapus detail & header
            $penerimaan->detail()->delete();
            $penerimaan->delete();

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // Ambil produk dari PO
    public function getProdukPO($id)
    {
        $poDetail = PurchaseOrderDetail::with('produk')->where('id_po', $id)->get();

        $data = $poDetail->map(function ($item) {
            return [
                'id_produk' => $item->id_produk,
                'nama' => $item->produk->nama,
                'qty' => $item->qty,
            ];
        });

        return response()->json($data);
    }

    public function getPenerimaanJson($id)
    {
        $penerimaan = PenerimaanPembelian::with('detail.produk')->findOrFail($id);
        return response()->json($penerimaan);
    }

    function tanggal_indonesia($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}
