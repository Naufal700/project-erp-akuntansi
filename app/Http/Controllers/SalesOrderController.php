<?php
// app/Http/Controllers/SalesOrderController.php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\Customer;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Exports\SalesOrderExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->latest()->get();
        return view('sales_order.index', compact('orders'));
    }

    public function create()
    {
        $customer = Customer::all();
        $products = Produk::all();
        return view('sales_order.create', compact('customer', 'products'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validasi stok produk
            foreach ($request->produk as $i => $produkId) {
                $produk = Produk::find($produkId);

                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                if ($produk->stok <= 0 || $produk->stok < $produk->stok_minimal) {
                    return back()->withErrors('Stok produk "' . $produk->nama . '" kosong atau di bawah stok minimal.');
                }
            }

            // Hitung total
            $total = 0;
            foreach ($request->produk as $i => $produkId) {
                $qty = $request->qty[$i];
                $harga = $request->harga[$i];
                $diskonPercent = $request->diskon[$i];

                $diskon = ($diskonPercent / 100) * ($qty * $harga);
                $subtotal = ($qty * $harga) - $diskon;
                $total += $subtotal;
            }

            $order = SalesOrder::create([
                'nomor_so' => 'SO-' . time(),
                'tanggal' => $request->tanggal,
                'id_customer' => $request->id_customer,
                'status' => 'pending',
                'total' => $total,
            ]);

            foreach ($request->produk as $i => $produkId) {
                $qty = $request->qty[$i];
                $harga = $request->harga[$i];
                $diskonPercent = $request->diskon[$i];
                $diskon = ($diskonPercent / 100) * ($qty * $harga);
                $subtotal = ($qty * $harga) - $diskon;

                SalesOrderDetail::create([
                    'id_so' => $order->id,
                    'id_produk' => $produkId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();

            return redirect()->route('sales_order.index')->with('success', 'Pesanan penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = SalesOrder::with(['customer', 'details.produk'])->findOrFail($id);
        return view('sales_order.show', compact('order'));
    }

    public function edit($id)
    {
        $order = SalesOrder::with('details')->findOrFail($id);
        $customer = Customer::all();
        $produk = Produk::all();
        return view('sales_order.edit', compact('order', 'customer', 'produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_customer' => 'required|exists:customer,id',
            'status' => 'required|in:pending,approved,rejected',
            'produk.*' => 'required|exists:produk,id',
            'qty.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
            'diskon.*' => 'required|numeric|min:0|max:100',
        ]);

        $order = SalesOrder::findOrFail($id);
        $order->tanggal = $request->tanggal;
        $order->id_customer = $request->id_customer;
        $order->status = $request->status;

        $total = 0;
        $details = [];

        $produkIds = $request->input('produk');
        $qtys = $request->input('qty');
        $hargas = $request->input('harga');
        $diskons = $request->input('diskon');

        for ($i = 0; $i < count($produkIds); $i++) {
            $harga = floatval($hargas[$i] ?? 0);
            $diskonPersen = floatval($diskons[$i] ?? 0);
            $qty = intval($qtys[$i] ?? 0);

            $diskonNominal = ($diskonPersen / 100) * $harga * $qty;
            $subtotal = ($harga * $qty) - $diskonNominal;

            $total += $subtotal;

            $details[] = [
                'id_produk' => $produkIds[$i],
                'qty' => $qty,
                'harga' => $harga,
                'diskon' => $diskonNominal, // simpan diskon dalam nominal rupiah
                'subtotal' => $subtotal,
            ];
        }

        $order->total = $total;
        $order->save();

        // Hapus detail lama lalu simpan detail baru
        $order->details()->delete();

        foreach ($details as $detail) {
            $order->details()->create($detail);
        }

        return redirect()->route('sales_order.index')->with('success', 'Sales order berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->details()->delete();
        $order->delete();

        return redirect()->route('sales_order.index')->with('success', 'Pesanan penjualan berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new SalesOrderExport, 'sales_orders.xlsx');
    }

    // Kalau ini method relasi, jangan di sini ya, harusnya di model SalesOrder.php
    // public function details()
    // {
    //     return $this->hasMany(SalesOrderDetail::class, 'id_so', 'id');
    // }

    public function reject($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->status = 'rejected';
        $order->save();

        return redirect()->route('sales_order.index')->with('success', 'Sales order telah dibatalkan.');
    }
}
