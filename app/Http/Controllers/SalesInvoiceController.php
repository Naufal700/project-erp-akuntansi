<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Coa;
use App\Models\JurnalUmum;
use App\Models\SalesOrder;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\MappingJurnal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // sesuaikan modelnya

class SalesInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesInvoice::with('salesOrder');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_invoice', 'like', "%{$search}%")
                ->orWhereHas('salesOrder', function ($q) use ($search) {
                    $q->where('nomor_so', 'like', "%{$search}%");
                });
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('sales_invoice.index', compact('invoices'));
    }

    public function create()
    {
        // Tampilkan sales order yang belum punya invoice
        $salesOrders = SalesOrder::doesntHave('salesInvoices')->get();
        return view('sales_invoice.create', compact('salesOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_so' => 'required|exists:sales_order,id',
            'tanggal' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
        ]);

        DB::beginTransaction();

        try {
            $so = SalesOrder::findOrFail($request->id_so);

            // Generate nomor invoice sederhana
            $nomorInvoice = 'INV-' . date('Ymd') . '-' . Str::random(5);

            $total = $so->total ?? 0;
            $ppn = round($total * 0.11, 2);

            $invoice = SalesInvoice::create([
                'nomor_invoice' => $nomorInvoice,
                'tanggal' => $request->tanggal,
                'id_so' => $request->id_so,
                'total' => $total,
                'ppn' => $ppn,
                'status' => 'belum_dibayar',
                'jatuh_tempo' => $request->jatuh_tempo,
            ]);

            $so->status = 'sudah invoice';
            $so->save();

            // Ambil data mapping jurnal untuk penjualan create
            $modul = 'penjualan';
            $event = 'faktur penjualan';

            $mapping = MappingJurnal::where('modul', $modul)
                ->where('event', $event)
                ->first();


            if ($mapping) {
                // Buat jurnal debit dan kredit
                // Asumsi: debit = piutang (kode_akun_debit), kredit = pendapatan (kode_akun_kredit)

                // Jurnal Debit (Piutang)
                JurnalUmum::create([
                    'tanggal' => $invoice->tanggal,
                    'kode_akun' => $mapping->kode_akun_debit,
                    'nominal_debit' => $total + $ppn,  // total + ppn sebagai piutang
                    'nominal_kredit' => 0,
                    'keterangan' => 'Penjualan Invoice #' . $invoice->nomor_invoice,
                    'ref' => 'sales_invoice',
                    'ref_id' => $invoice->id,
                    'modul' => 'penjualan',
                    'event' => 'faktur penjualan'
                ]);

                // Jurnal Kredit (Pendapatan)
                JurnalUmum::create([
                    'tanggal' => $invoice->tanggal,
                    'kode_akun' => $mapping->kode_akun_kredit,
                    'nominal_debit' => 0,
                    'nominal_kredit' => $total,  // hanya DPP sebagai pendapatan
                    'keterangan' => 'Pendapatan Penjualan Invoice #' . $invoice->nomor_invoice,
                    'ref' => 'sales_invoice',
                    'ref_id' => $invoice->id,
                    'modul' => 'penjualan',
                    'event' => 'faktur penjualan'
                ]);

                // Jurnal Kredit PPN Keluaran
                // Jika PPN ingin dicatat terpisah di akun khusus, bisa ambil dari mapping lain atau hardcode
                // Contoh hardcode kode akun ppn keluaran: '411110'
                $akunPPN = Coa::where('nama_akun', 'PPN Keluaran')->first();

                if (!$akunPPN) {
                    throw new \Exception('Akun PPN Keluaran tidak ditemukan di tabel COA');
                }

                $kodeAkunPPN = $akunPPN->kode_akun;
                // sesuaikan dengan COA kamu

                if ($ppn > 0) {
                    JurnalUmum::create([
                        'tanggal' => $invoice->tanggal,
                        'kode_akun' => $kodeAkunPPN,
                        'nominal_debit' => 0,
                        'nominal_kredit' => $ppn,
                        'keterangan' => 'PPN Keluaran Invoice #' . $invoice->nomor_invoice,
                        'ref' => 'sales_invoice',
                        'ref_id' => $invoice->id,
                        'modul' => 'penjualan',
                        'event' => 'faktur penjualan'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('sales-invoice.index')->with('success', 'Invoice berhasil dibuat dan status SO diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Ambil data invoice lengkap dengan salesOrder, details, dan customer
        $invoice = SalesInvoice::with([
            'salesOrder.details.produk',  // load details dan produk terkait
            'salesOrder.customer'         // load data customer
        ])->findOrFail($id);

        return view('sales_invoice.show', compact('invoice'));
    }
    public function printPdf($id)
    {
        $invoice = SalesInvoice::with(['salesOrder.customer', 'salesOrder.details.produk'])->findOrFail($id);

        $pdf = PDF::loadView('sales_invoice.pdf', compact('invoice'));

        // opsi: download atau stream
        return $pdf->download('FakturPenjualan-' . $invoice->nomor_invoice . '.pdf');
        // atau jika mau langsung ditampilkan di browser:
        // return $pdf->stream('FakturPenjualan-' . $invoice->nomor_invoice . '.pdf');
    }
    public function destroy($id)
    {
        $invoice = SalesInvoice::findOrFail($id);

        // Bisa tambahkan cek status invoice sebelum hapus jika perlu
        $invoice->delete();

        return redirect()->route('sales-invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function cancel($id)
    {
        $invoice = SalesInvoice::findOrFail($id);

        // Ambil sales order terkait sebelum invoice dihapus
        $so = $invoice->salesOrder;

        // Hapus invoice
        $invoice->delete();

        // Kembalikan status SO jadi pending
        if ($so) {
            $so->status = 'pending';
            $so->save();
        }

        return redirect()->route('sales-invoice.index')->with('success', 'Invoice berhasil dibatalkan dan dihapus. Status SO dikembalikan ke pending.');
    }
}
