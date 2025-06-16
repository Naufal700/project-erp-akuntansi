<?php

namespace App\Exports;

use App\Models\SalesInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PiutangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SalesInvoice::with('customer')->get()->map(function ($item) {
            return [
                'Nomor Invoice' => $item->nomor_invoice,
                'Nama Customer' => $item->customer->nama ?? '-',
                'Tanggal' => $item->tanggal,
                'Jatuh Tempo' => $item->jatuh_tempo,
                'Total' => $item->total + $item->ppn,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nomor Invoice',
            'Nama Customer',
            'Tanggal',
            'Jatuh Tempo',
            'Total',
            'Status'
        ];
    }
}
