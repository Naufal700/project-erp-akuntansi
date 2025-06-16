<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PiutangExport;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->input('filter_status');

        // Query dasar: Join ke sales_order dan customer
        $query = DB::table('sales_invoice')
            ->join('sales_order', 'sales_invoice.id_so', '=', 'sales_order.id')
            ->join('customer', 'sales_order.id_customer', '=', 'customer.id')
            ->select(
                'sales_invoice.nomor_invoice',
                'sales_invoice.tanggal',
                'sales_invoice.jatuh_tempo',
                'sales_invoice.status',
                'sales_invoice.total',
                'sales_invoice.ppn',
                'customer.nama as nama_customer'
            )
            ->orderBy('sales_invoice.jatuh_tempo', 'asc');

        // Terapkan filter jika ada
        if ($filterStatus == 'belum') {
            $query->whereIn('sales_invoice.status', ['belum_dibayar', 'partial']);
        } elseif ($filterStatus == 'lunas') {
            $query->where('sales_invoice.status', 'lunas');
        }

        $semuaFaktur = $query->get();

        // Hitung total berdasarkan status
        $totalPiutang = SalesInvoice::where('status', '!=', 'lunas')->sum(DB::raw('total + ppn'));
        $piutangBelumJatuhTempo = SalesInvoice::where('status', '!=', 'lunas')
            ->where('jatuh_tempo', '>=', Carbon::today())
            ->sum(DB::raw('total + ppn'));
        $piutangJatuhTempo = SalesInvoice::where('status', '!=', 'lunas')
            ->where('jatuh_tempo', '<', Carbon::today())
            ->sum(DB::raw('total + ppn'));
        $piutangLunas = SalesInvoice::where('status', 'lunas')->sum(DB::raw('total + ppn'));

        return view('piutang.index', compact(
            'totalPiutang',
            'piutangBelumJatuhTempo',
            'piutangJatuhTempo',
            'piutangLunas',
            'semuaFaktur',
            'filterStatus'
        ));
    }

    public function exportPdf()
    {
        $data = SalesInvoice::with('customer')
            ->orderBy('jatuh_tempo', 'asc')
            ->get();

        $pdf = PDF::loadView('piutang.export_pdf', compact('data'));
        return $pdf->download('laporan_piutang.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PiutangExport, 'laporan_piutang.xlsx');
    }
}
