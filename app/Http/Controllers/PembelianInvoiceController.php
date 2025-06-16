<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PembelianInvoice;
use App\Models\FakturPajakMasukan;
use Illuminate\Support\Facades\DB;
use App\Models\PenerimaanPembelian;
use App\Models\PembelianInvoiceDetail;

class PembelianInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = PembelianInvoice::with('penerimaan.purchaseOrder.supplier')->latest();

        if ($request->filled('search')) {
            $query->where('nomor_invoice', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(10);
        return view('pembelian_invoice.index', compact('invoices'));
    }
    public function create()
    {
        $poList = PenerimaanPembelian::with('purchaseOrder.supplier')
            ->where('status', 'belum_faktur')
            ->get();

        return view('pembelian_invoice.create', compact('poList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_invoice' => 'required|string|max:50|unique:pembelian_invoice,nomor_invoice',
            'id_po' => 'required|exists:penerimaan_pembelian,id',
            'tanggal' => 'required|date',
            'jatuh_tempo' => 'nullable|date',
            'nomor_faktur_pajak' => 'nullable|string|max:50',
            'tanggal_faktur_pajak' => 'nullable|date',
            'produk' => 'required|array',
            'produk.*.id' => 'required|exists:produk,id',
            'produk.*.qty' => 'required|numeric|min:1',
            'produk.*.harga' => 'required|numeric|min:0',
            'produk.*.diskon' => 'nullable|numeric|min:0',
            'gunakan_ppn' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $totalDiskon = 0;
            $detailItems = [];

            foreach ($request->produk as $item) {
                $qty = $item['qty'];
                $harga = $item['harga'];
                $diskon = $item['diskon'] ?? 0;
                $total = ($qty * $harga) - $diskon;

                $subtotal += $qty * $harga;
                $totalDiskon += $diskon;

                $detailItems[] = [
                    'id_produk' => $item['id'],
                    'qty' => $qty,
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'total' => $total,
                ];
            }

            $ppn = ($request->gunakan_ppn == 1) ? ($subtotal - $totalDiskon) * 0.11 : 0;
            $grandTotal = $subtotal - $totalDiskon + $ppn;

            $penerimaan = PenerimaanPembelian::findOrFail($request->id_po); // ini sebenarnya id_penerimaan

            $invoice = PembelianInvoice::create([
                'nomor_invoice' => $request->nomor_invoice,
                'tanggal' => $request->tanggal,
                'id_po' => $penerimaan->id_po,
                'subtotal' => $subtotal,
                'diskon' => $totalDiskon,
                'ppn' => $ppn,
                'total' => $grandTotal,
                'status' => 'belum_dikontrabon',
                'jatuh_tempo' => $request->jatuh_tempo,
                'nomor_faktur_pajak' => $request->nomor_faktur_pajak,
                'tanggal_faktur_pajak' => $request->tanggal_faktur_pajak,
            ]);

            foreach ($detailItems as $item) {
                $item['id_invoice'] = $invoice->id;
                PembelianInvoiceDetail::create($item);
            }

            PenerimaanPembelian::where('id', $request->id_po)->update([
                'status' => 'sudah_faktur'
            ]);

            if ($ppn > 0) {
                FakturPajakMasukan::create([
                    'id_invoice' => $invoice->id,
                    'nomor_faktur_pajak' => $request->nomor_faktur_pajak,
                    'tanggal_faktur_pajak' => $request->tanggal_faktur_pajak,
                    'nilai_dpp' => $subtotal - $totalDiskon,
                    'nilai_ppn' => $ppn,
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian-invoice.index', $invoice->id)
                ->with('success', 'Faktur pembelian berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan faktur: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $invoice = PembelianInvoice::with([
            'penerimaan.purchaseOrder.supplier',
            'details.produk'
        ])->findOrFail($id);

        return view('pembelian_invoice.show', compact('invoice'));
    }
    public function getPenerimaan($id)
    {
        $penerimaan = \App\Models\PenerimaanPembelian::with([
            'purchaseOrder.supplier',
            'details.produk',
            'details.purchaseOrderDetail' // penting!
        ])->findOrFail($id);

        return response()->json([
            'po' => $penerimaan->purchaseOrder,
            'details' => $penerimaan->details->map(function ($item) use ($penerimaan) {
                $hargaPO = \App\Models\PurchaseOrderDetail::where('id_po', $penerimaan->id_po)
                    ->where('id_produk', $item->id_produk)
                    ->value('harga') ?? 0;

                return [
                    'produk' => $item->produk,
                    'qty_diterima' => $item->qty_diterima,
                    'harga_po' => $hargaPO,
                    'harga' => $hargaPO, // default harga faktur = harga PO
                ];
            })
        ]);
    }
    public function batal($id)
    {
        DB::beginTransaction();

        try {
            $invoice = PembelianInvoice::with(['penerimaan'])->findOrFail($id);

            if ($invoice->status !== 'belum_dikontrabon') {
                return redirect()->back()->with('error', 'Faktur sudah dikontrabon dan tidak dapat dibatalkan.');
            }

            // Kembalikan status penerimaan (jika ada)
            if ($invoice->penerimaan) {
                $invoice->penerimaan->update(['status' => 'belum_faktur']);
            }

            // Hapus faktur pajak masukan terkait
            FakturPajakMasukan::where('id_invoice', $invoice->id)->delete();

            // Hapus detail invoice
            PembelianInvoiceDetail::where('id_invoice', $invoice->id)->delete();

            // Hapus faktur utama
            $invoice->delete();

            DB::commit();
            return redirect()->route('pembelian-invoice.index')->with('success', 'Faktur pembelian berhasil dibatalkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan faktur: ' . $e->getMessage());
        }
    }
}
