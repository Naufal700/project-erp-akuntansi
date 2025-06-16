<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateGudangExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'kode_gudang',
            'nama_gudang',
            'alamat',
            'keterangan',
        ];
    }
}
