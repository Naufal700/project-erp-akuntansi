<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesOrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SalesOrder::with('customer', 'details')->get()->map(function ($order) {
            $totalHarga = $order->details->sum(fn($d) => $d->harga * $d->qty);
            $totalDiskon = $order->details->sum('diskon');
            $hargaSetelahDiskon = $totalHarga - $totalDiskon;
            $totalPPN = ($order->ppn / 100) * $hargaSetelahDiskon;
            $hargaBersih = $hargaSetelahDiskon + $totalPPN;

            return [
                'Nomor SO' => $order->nomor_so,
                'Tanggal' => $order->tanggal->format('d-m-Y'),
                'Customer' => $order->customer->nama,
                'Status' => ucfirst($order->status),
                'Total Harga' => $totalHarga,
                'Total Diskon' => $totalDiskon,
                'Total PPN' => $totalPPN,
                'Harga Bersih' => $hargaBersih,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nomor SO',
            'Tanggal',
            'Customer',
            'Status',
            'Total Harga',
            'Total Diskon',
            'Total PPN',
            'Harga Bersih',
        ];
    }
}
