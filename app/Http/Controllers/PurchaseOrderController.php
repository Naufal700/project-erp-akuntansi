<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier')->latest();

        // Filter tanggal jika tersedia
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // Filter berdasarkan keyword (nomor_po atau nama supplier)
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_po', 'like', '%' . $keyword . '%')
                    ->orWhereHas('supplier', function ($s) use ($keyword) {
                        $s->where('nama', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $data = $query->get();

        return view('purchase_order.index', compact('data'));
    }

    public function create()
    {
        $last = PurchaseOrder::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $nomor_po = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchase_order.create', [
            'nomor_po' => $nomor_po,
            'suppliers' => Supplier::all(),
            'produks' => Produk::all()
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nomor_po' => 'required|unique:purchase_order',
            'tanggal' => 'required|date',
            'id_supplier' => 'required',
            'produk.*.id_produk' => 'required',
            'produk.*.qty' => 'required|numeric',
            'produk.*.harga' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->produk as $item) {
                $total += $item['qty'] * $item['harga'];
            }

            $po = PurchaseOrder::create([
                'nomor_po' => $request->nomor_po,
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'status' => 'draft',
                'total' => $total
            ]);

            foreach ($request->produk as $item) {
                PurchaseOrderDetail::create([
                    'id_po' => $po->id,
                    'id_produk' => $item['id_produk'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga']
                ]);
            }
        });

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function edit($id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);
        $suppliers = Supplier::all();
        $produks = Produk::all();
        return view('purchase_order.edit', compact('po', 'suppliers', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        DB::transaction(function () use ($request, $po) {
            $po->update($request->only(['tanggal', 'id_supplier', 'status']));

            $po->details()->delete();
            $total = 0;
            foreach ($request->produk as $item) {
                $total += $item['qty'] * $item['harga'];
                PurchaseOrderDetail::create([
                    'id_po' => $po->id,
                    'id_produk' => $item['id_produk'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga']
                ]);
            }
            $po->update(['total' => $total]);
        });

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil diupdate.');
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['supplier', 'details.produk'])->findOrFail($id);
        return view('purchase_order.show', compact('po'));
    }
    public function destroy($id)
    {
        $po = PurchaseOrder::with('penerimaan')->findOrFail($id);

        // Cek apakah PO sudah ada penerimaan
        if ($po->penerimaan && $po->penerimaan->count() > 0) {
            return redirect()->route('purchase-order.index')
                ->with('error', 'PO tidak bisa dihapus karena barang sudah diterima.');
        }

        // Hapus detail PO jika relasi ada
        $po->details()->delete();

        // Hapus PO
        $po->delete();

        return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil dihapus.');
    }
}
