<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\FakturPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PpnKeluaranExport;
use Maatwebsite\Excel\Facades\Excel;

class PPNKeluaranController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::with([
            'salesOrder.customer',
            'salesOrder.salesOrderDetails'
        ])->get();

        $data = $invoices->map(function ($invoice) {
            $salesOrder = $invoice->salesOrder;
            $details = $salesOrder->salesOrderDetails;

            $dpp = $details->sum('subtotal'); // Jumlahkan semua subtotal
            $ppn = $dpp * 0.11;
            $total = $dpp + $ppn;

            return [
                'nomor_faktur' => $invoice->nomor_invoice,
                'tanggal'      => $invoice->tanggal,
                'customer'     => optional($salesOrder->customer)->nama ?? '-',
                'dpp'          => $dpp,
                'ppn'          => $ppn,
                'total'        => $total,
            ];
        });

        return view('ppn.keluaran.index', compact('data'));
    }
    public function exportExcel()
    {
        return Excel::download(new PpnKeluaranExport, 'ppn-keluaran.xlsx');
    }

    public function exportPDF()
    {
        $invoices = SalesInvoice::with('salesOrder.salesOrderDetail.customer')->get();

        $data = $invoices->map(function ($invoice) {
            $salesOrder = $invoice->salesOrder;
            $details = $salesOrder->salesOrderDetails;

            $dpp = $details->sum('subtotal'); // Jumlahkan semua subtotal
            $ppn = $dpp * 0.11;
            $total = $dpp + $ppn;


            return [
                'nomor_faktur' => $invoice->nomor_invoice,
                'tanggal'      => $invoice->tanggal,
                'customer'     => optional($salesOrder->customer)->nama ?? '-',
                'dpp'          => $dpp,
                'ppn'          => $ppn,
                'total'        => $total,
            ];
        });

        $pdf = PDF::loadView('ppn.keluaran.pdf', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream('ppn-keluaran.pdf');
    }
}
