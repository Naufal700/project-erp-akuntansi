<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class CoaExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // Contoh data kosong sebagai petunjuk
            ['1001', 'Kas', 'Aset', '', 1, 0],
            ['2001', 'Hutang Usaha', 'Kewajiban', '', 1, 0],
        ];
    }

    public function headings(): array
    {
        return [
            'kode_akun',
            'nama_akun',
            'tipe_akun',
            'parent_kode',
            'level',
            'saldo_awal',
        ];
    }
}
