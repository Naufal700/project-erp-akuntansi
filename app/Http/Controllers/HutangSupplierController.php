<?php

namespace App\Http\Controllers;

use App\Models\PembelianInvoice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HutangSupplierExport;
use Barryvdh\DomPDF\Facade\Pdf;

class HutangSupplierController extends Controller
{
    public function index()
    {
        $invoices = PembelianInvoice::with([
            'purchaseOrder.supplier',
            'kontrabonDetail.kontrabon'
        ])->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jatuh_tempo' => $item->jatuh_tempo,
                'nomor_invoice' => $item->nomor_invoice,
                'nomor_kontrabon' => optional(optional($item->kontrabonDetail)->kontrabon)->nomor_kontrabon,
                'supplier' => optional($item->purchaseOrder?->supplier)->nama ?? '-',
                'total' => $item->total,
                'dibayar' => $item->status === 'dibayar' ? $item->total : 0,
                'sisa' => $item->status === 'lunas' ? 0 : $item->total,
                'status' => $item->status,
            ];
        });
        return view('hutang_supplier.index', compact('invoices'));
    }

    public function exportExcel()
    {
        return Excel::download(new HutangSupplierExport, 'daftar_hutang_supplier.xlsx');
    }

    public function exportPdf()
    {
        $invoices = PembelianInvoice::with(['supplier', 'pembayaran', 'kontrabonDetail.kontrabon'])
            ->whereIn('status', ['belum_dibayar', 'dicicil'])
            ->get()
            ->map(function ($item) {
                $dibayar = $item->pembayaran->sum('jumlah');
                $sisa    = $item->total - $dibayar;
                return [
                    'tanggal' => $item->tanggal,
                    'jatuh_tempo' => $item->jatuh_tempo,
                    'nomor_invoice' => $item->nomor_invoice,
                    'nomor_kontrabon' => optional(optional($item->kontrabonDetail)->kontrabon)->nomor_kontrabon,
                    'supplier' => optional($item->supplier)->nama ?? '-',
                    'total' => $item->total,
                    'dibayar' => $dibayar,
                    'sisa' => $sisa,
                    'status' => $item->status,
                ];
            });

        $pdf = PDF::loadView('hutang_supplier.pdf', compact('invoices'));
        return $pdf->stream('daftar_hutang_supplier.pdf');
    }
}
