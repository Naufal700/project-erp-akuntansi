<?php

namespace App\Http\Controllers;

use App\Models\JurnalUmum;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\MappingJurnal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;
use App\Models\PembayaranPenjualan;

class PembayaranPenjualanController extends Controller
{
    public function index()
    {
        // Ambil data invoice dengan total pembayaran < total tagihan invoice
        $invoices = SalesInvoice::select('sales_invoice.*', DB::raw('
        COALESCE(
            (SELECT SUM(jumlah) FROM pembayaran_penjualan WHERE id_invoice = sales_invoice.id), 0
        ) as total_bayar
    '))
            ->whereRaw('
            COALESCE(
                (SELECT SUM(jumlah) FROM pembayaran_penjualan WHERE id_invoice = sales_invoice.id), 0
            ) < sales_invoice.total
        ')
            ->with([
                'salesOrder.customer:id,nama' // join ke customer melalui sales order
            ])
            ->get();

        // Ambil semua pembayaran penjualan beserta invoice, metode pembayaran
        $data = PembayaranPenjualan::with([
            'invoice.salesOrder.customer:id,nama',
            'metodePembayaran:id,nama'
        ])->get();

        return view('pembayaran_penjualan.index', compact('data', 'invoices'));
    }



    public function create()
    {
        // Query invoice yang belum lunas dan join dengan customer untuk nama customer
        $invoices = SalesInvoice::select(
            'sales_invoice.*',
            'customer.nama as nama_customer',
            DB::raw('
        COALESCE(
            (SELECT SUM(jumlah) FROM pembayaran_penjualan WHERE id_invoice = sales_invoice.id), 0
        ) as total_bayar
    ')
        )
            ->join('sales_order', 'sales_invoice.id_so', '=', 'sales_order.id') // join ke sales_order dulu
            ->join('customer', 'sales_order.id_customer', '=', 'customer.id') // join ke customer
            ->whereRaw('
    COALESCE(
        (SELECT SUM(jumlah) FROM pembayaran_penjualan WHERE id_invoice = sales_invoice.id), 0
    ) < sales_invoice.total
')
            ->get();

        $metodePembayaran = MetodePembayaran::all();

        return view('pembayaran_penjualan.create', compact('invoices', 'metodePembayaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_invoice' => 'required|exists:sales_invoice,id',
            'tanggal' => 'required|date',
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        // Simpan pembayaran
        $pembayaran = PembayaranPenjualan::create([
            'id_invoice' => $request->id_invoice,
            'tanggal' => $request->tanggal,
            'id_metode_pembayaran' => $request->id_metode_pembayaran,
            'jumlah' => $request->jumlah,
        ]);

        // Update status invoice
        $invoice = SalesInvoice::findOrFail($request->id_invoice);
        $totalBayar = $invoice->pembayaranPenjualan()->sum('jumlah');

        if ($totalBayar >= $invoice->total) {
            $invoice->status = 'lunas';
        } else {
            $invoice->status = 'belum_lunas';
        }
        $invoice->save();

        $modul = 'penjualan';
        $event = 'pembayaran penjualan';

        $mapping = MappingJurnal::where('modul', $modul)
            ->where('event', $event)
            ->first();

        $metode = MetodePembayaran::findOrFail($request->id_metode_pembayaran);
        $akunKasBank = $metode->kode_akun;

        if (!$mapping || !$akunKasBank) {
            // Kalau gagal, kita rollback dan hentikan eksekusi
            DB::rollBack();
            return back()->withErrors('Mapping jurnal atau akun kas/bank tidak ditemukan.');
        }

        // Buat jurnal debit kas/bank
        JurnalUmum::create([
            'tanggal' => $pembayaran->tanggal,
            'kode_akun' => $akunKasBank,
            'nominal_debit' => $request->jumlah,
            'nominal_kredit' => 0,
            'keterangan' => 'Pembayaran invoice #' . $invoice->nomor_invoice,
            'ref' => 'sales_payment',
            'ref_id' => $pembayaran->id,
            'modul' => $modul,
            'event' => $event,
        ]);

        // Buat jurnal kredit piutang
        JurnalUmum::create([
            'tanggal' => $pembayaran->tanggal,
            'kode_akun' => $mapping->kode_akun_kredit,
            'nominal_debit' => 0,
            'nominal_kredit' => $request->jumlah,
            'keterangan' => 'Pelunasan piutang invoice #' . $invoice->nomor_invoice,
            'ref' => 'sales_payment',
            'ref_id' => $pembayaran->id,
            'modul' => $modul,
            'event' => $event,
        ]);

        DB::commit();

        return redirect()->route('pembayaran-penjualan.index')->with('success', 'Pembayaran berhasil dicatat.');
    }


    public function getInvoiceTotal($id)
    {
        try {
            $invoice = SalesInvoice::with('salesOrder.details.produk')->findOrFail($id);

            $totalBelumBayar = $invoice->total - $invoice->total_bayar;

            $produkBelumDibayar = [];

            if ($totalBelumBayar > 0) {
                $produkBelumDibayar = $invoice->salesOrder->details->map(function ($item) {
                    return [
                        'nama' => $item->produk ? $item->produk->nama : 'Produk tidak ditemukan',
                        'qty' => $item->qty,
                        'subtotal' => $item->subtotal,
                    ];
                })->values();
            }

            return response()->json([
                'success' => true,
                'total' => $totalBelumBayar > 0 ? $totalBelumBayar : 0,
                'produk' => $produkBelumDibayar,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data. ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getProdukBelumBayar($id)
    {
        try {
            $invoice = SalesInvoice::with('salesOrder.salesOrderDetails.produk')->findOrFail($id);

            $produkBelumDibayar = $invoice->salesOrder->salesOrderDetails->map(function ($detail) {
                $hargaSetelahDiskon = $detail->harga - $detail->diskon;
                $subtotalTanpaPpn = $hargaSetelahDiskon * $detail->qty;
                $ppn = $subtotalTanpaPpn * 0.11; // 11% PPN
                $subtotal = $subtotalTanpaPpn + $ppn;

                return [
                    'id_detail' => $detail->id,
                    'nama' => $detail->produk->nama ?? '-',
                    'qty' => $detail->qty,
                    'harga' => $detail->harga,
                    'diskon' => $detail->diskon,
                    'ppn' => $ppn,
                    'subtotal' => $subtotal,
                ];
            });

            return response()->json([
                'success' => true,
                'produk' => $produkBelumDibayar,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
    public function destroy($id)
    {
        $pembayaran = PembayaranPenjualan::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayaran-penjualan.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
    public function batal($id)
    {
        $pembayaran = PembayaranPenjualan::findOrFail($id);

        // Update status invoice menjadi belum_dibayar
        $invoice = $pembayaran->invoice;
        $invoice->status = 'belum_dibayar';
        $invoice->save();

        // Hapus pembayaran
        $pembayaran->delete();

        return redirect()->route('pembayaran-penjualan.index')->with('success', 'Pembayaran berhasil dibatalkan dan status invoice dikembalikan.');
    }
    public function show($id)
    {
        $pembayaran = PembayaranPenjualan::with('invoice', 'metodePembayaran')->findOrFail($id);
        return view('pembayaran_penjualan.show', compact('pembayaran'));
    }
    public function cetakPdf($id)
    {
        $pembayaran = PembayaranPenjualan::with('invoice', 'metodePembayaran')->findOrFail($id);

        $pdf = PDF::loadView('pembayaran_penjualan.cetak_pdf', compact('pembayaran'));
        $filename = 'Pembayaran-' . $pembayaran->invoice->nomor_invoice . '.pdf';

        // Bisa langsung download:
        // return $pdf->download($filename);

        // Atau buka di tab baru (stream):
        return $pdf->stream($filename);
    }
}
