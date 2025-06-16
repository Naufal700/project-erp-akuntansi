<?php

namespace App\Exports;

use App\Models\SalesInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PpnKeluaranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $invoices = SalesInvoice::with('salesOrder.salesOrderDetail.customer')->get();

        $data = $invoices->map(function ($invoice) {
            $salesOrder = $invoice->salesOrder;
            $details = $salesOrder->salesOrderDetails;

            $dpp = $details->sum('subtotal'); // Jumlahkan semua subtotal
            $ppn = $dpp * 0.11;
            $total = $dpp + $ppn;

            return collect([
                'Nomor Faktur' => $invoice->nomor_invoice,
                'Tanggal'      => $invoice->tanggal->format('d-m-Y'),
                'customer'     => optional($salesOrder->customer)->nama ?? '-',
                'DPP'          => $dpp,
                'PPN (11%)'    => $ppn,
                'Total Faktur' => $total,
            ]);
        });

        return new Collection($data);
    }

    public function headings(): array
    {
        return ['Nomor Faktur', 'Tanggal', 'Customer', 'DPP', 'PPN (11%)', 'Total Faktur'];
    }
}
