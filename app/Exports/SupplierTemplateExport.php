<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class SupplierTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'nama' => '',
                'alamat' => '',
                'telepon' => '',
                'email' => '',
            ],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'alamat', 'telepon', 'email'];
    }
}
