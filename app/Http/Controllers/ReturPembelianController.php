<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ReturPembelian;
use App\Models\ReturPembelianDetail;
use App\Models\PembelianInvoice;
use App\Models\PenerimaanPembelian;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ReturPembelianController extends Controller
{
    public function index()
    {
        $retur = ReturPembelian::with('supplier', 'penerimaan')->latest()->paginate(10);
        return view('retur_pembelian.index', compact('retur'));
    }

    public function create()
    {
        $penerimaan = PenerimaanPembelian::with(
            'purchaseOrder.supplier',
            'purchaseOrder.invoice.detail.produk'
        )->get();

        $invoice = PembelianInvoice::all();
        $produk = Produk::all();
        $supplier = Supplier::all();

        $last = ReturPembelian::latest('id')->first();
        $lastNumber = $last ? (int) Str::after($last->nomor_retur, 'RPB-') : 0;
        $nextNumber = 'RPB-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return view('retur_pembelian.create', compact('supplier', 'penerimaan', 'invoice', 'produk', 'nextNumber'));
    }


    public function store(Request $request)
    {
        // Validasi input header dan detail retur
        $request->validate([
            'nomor_retur' => 'required|unique:retur_pembelian,nomor_retur',
            'tanggal' => 'required|date',
            'id_penerimaan' => 'required|exists:penerimaan_pembelian,id',
            'total' => 'required|numeric',
            'detail' => 'required|array|min:1',
            'detail.*.id_produk' => 'required|exists:produk,id',
            'detail.*.qty_retur' => 'required|numeric|min:1',
            'detail.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data penerimaan dan supplier
            $penerimaan = PenerimaanPembelian::with('purchaseOrder.supplier')->findOrFail($request->id_penerimaan);
            $supplier = $penerimaan->purchaseOrder->supplier ?? null;

            if (!$supplier) {
                return back()->withInput()->withErrors([
                    'id_penerimaan' => 'Supplier tidak ditemukan untuk penerimaan ini.'
                ]);
            }

            // Simpan header retur pembelian
            $retur = ReturPembelian::create([
                'nomor_retur' => $request->nomor_retur,
                'tanggal' => $request->tanggal,
                'id_supplier' => $supplier->id,
                'id_penerimaan' => $request->id_penerimaan,
                'id_invoice' => $request->id_invoice ?? null,
                'keterangan' => $request->keterangan,
                'total' => $request->total,
                'nilai_nota_kredit' => 0, // diupdate setelah hitung detail
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            // Simpan detail retur dan hitung nilai nota kredit
            $nilaiNotaKredit = 0;

            foreach ($request->detail as $detail) {
                $subtotal = $detail['qty_retur'] * $detail['harga_satuan'];
                $nilaiNotaKredit += $subtotal;

                ReturPembelianDetail::create([
                    'id_retur' => $retur->id,
                    'id_produk' => $detail['id_produk'],
                    'qty_retur' => $detail['qty_retur'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'subtotal' => $subtotal,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            // Update nilai_nota_kredit
            $retur->update([
                'nilai_nota_kredit' => $nilaiNotaKredit,
            ]);

            // Jika retur terkait invoice, update invoice
            if ($request->filled('id_invoice')) {
                $invoice = PembelianInvoice::find($request->id_invoice);

                if ($invoice) {
                    $invoice->total_retur = ($invoice->total_retur ?? 0) + $nilaiNotaKredit;

                    // Hitung sisa tagihan
                    $sisa = $invoice->total - $invoice->total_retur - ($invoice->dibayar ?? 0);

                    // Update status invoice otomatis
                    if ($sisa <= 0) {
                        $invoice->status = 'lunas';
                    } elseif ($invoice->dibayar > 0) {
                        $invoice->status = 'dibayar_sebagian';
                    } else {
                        $invoice->status = 'belum_dikontrabon';
                    }

                    $invoice->save();
                }
            }

            DB::commit();
            return redirect()->route('retur-pembelian.index')->with('success', 'Retur pembelian berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan retur: ' . $th->getMessage());
        }
    }
}
