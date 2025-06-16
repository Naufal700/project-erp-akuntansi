<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class CustomerExport implements FromArray, WithHeadings
{
    // Buat template kosong dengan header kolom saja
    public function array(): array
    {
        return [
            // data kosong, hanya header yang ditampilkan
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Alamat',
            'Telepon',
            'Email',
        ];
    }
}
