<?php

namespace App\Http\Controllers;

use App\Models\Kontrabon;
use App\Models\KontrabonDetail;
use App\Models\Supplier;
use App\Models\PembelianInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class KontrabonController extends Controller
{
    public function index(Request $request)
    {
        $query = Kontrabon::with(['supplier', 'details'])->latest();

        // Filter pencarian berdasarkan nomor kontrabon atau nama supplier
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_kontrabon', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($qs) use ($search) {
                        $qs->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter tanggal awal dan akhir
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kontrabon = $query->get();

        return view('kontrabon.index', compact('kontrabon'));
    }
    public function create()
    {
        $suppliers = Supplier::all();

        // Generate nomor kontrabon otomatis
        $prefix = 'KB-' . date('Ym');
        $last = Kontrabon::where('nomor_kontrabon', 'like', $prefix . '%')
            ->orderBy('nomor_kontrabon', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int)substr($last->nomor_kontrabon, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $generatedNumber = $prefix . $nextNumber;

        return view('kontrabon.create', compact('suppliers', 'generatedNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_kontrabon' => 'required|unique:kontrabon,nomor_kontrabon',
            'tanggal' => 'required|date',
            'id_supplier' => 'required',
            'id_invoice' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $total = PembelianInvoice::whereIn('id', $request->id_invoice)->sum('total');

            $kontrabon = Kontrabon::create([
                'nomor_kontrabon' => $request->nomor_kontrabon,
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'total' => $total,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->id_invoice as $id_invoice) {
                // Ambil data invoice-nya
                $invoice = PembelianInvoice::find($id_invoice);

                // Simpan ke kontrabon_detail
                KontrabonDetail::create([
                    'id_kontrabon' => $kontrabon->id,
                    'id_invoice' => $id_invoice
                ]);

                // Update status dan nilai dibayar pada invoice
                $invoice->update([
                    'status' => 'dikontrabon',
                    'dibayar' => $invoice->total, // ← ini bagian tambahan
                ]);
            }
        });

        return redirect()->route('kontrabon.index')->with('success', 'Kontrabon berhasil dibuat.');
    }

    public function show($id)
    {
        $kontrabon = Kontrabon::with('supplier', 'details.invoice')->findOrFail($id);
        return view('kontrabon.show', compact('kontrabon'));
    }

    public function getInvoicesBySupplier(Request $request)
    {
        $supplierId = $request->supplier_id;

        $invoices = PembelianInvoice::where('status', 'belum_dikontrabon')
            ->whereHas('purchaseOrder', function ($q) use ($supplierId) {
                $q->where('id_supplier', $supplierId);
            })
            ->whereNotIn('id', function ($q) {
                $q->select('id_invoice')->from('kontrabon_detail');
            })
            ->get();

        return response()->json($invoices->map(function ($inv) {
            return [
                'id' => $inv->id,
                'nomor_invoice' => $inv->nomor_invoice,
                'tanggal' => $inv->tanggal,
                'total' => $inv->total,
            ];
        }));
    }
    public function batal($id)
    {
        DB::transaction(function () use ($id) {
            $kontrabon = Kontrabon::findOrFail($id);

            // Ambil semua invoice terkait
            $detailInvoices = KontrabonDetail::where('id_kontrabon', $kontrabon->id)->get();

            foreach ($detailInvoices as $detail) {
                // Kembalikan status invoice ke 'belum_dibayar'
                PembelianInvoice::where('id', $detail->id_invoice)->update([
                    'status' => 'belum_dikontrabon'
                ]);
            }

            // Hapus detail kontrabon
            KontrabonDetail::where('id_kontrabon', $kontrabon->id)->delete();

            // Hapus kontrabon
            $kontrabon->delete();
        });

        return redirect()->route('kontrabon.index')->with('success', 'Kontrabon berhasil dibatalkan.');
    }
    // app/Http/Controllers/KontrabonController.php
    public function cetak($id)
    {
        $kontrabon = Kontrabon::with(['supplier', 'details.invoice'])->findOrFail($id);

        $pdf = PDF::loadView('kontrabon.pdf', compact('kontrabon'))->setPaper('A4');

        return $pdf->stream('Kontrabon-' . $kontrabon->nomor_kontrabon . '.pdf');
    }
}
