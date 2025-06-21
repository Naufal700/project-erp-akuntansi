<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class CoaExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // Contoh data kosong sebagai petunjuk format
            ['1001', 'Kas', 'Kas', '', 2, 1000000, 0, '2025-01'],
            ['2001', 'Hutang Usaha', 'Kewajiban', '', 2, 0, 500000, '2025-01'],
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
            'saldo_awal_debit',
            'saldo_awal_kredit',
            'periode_saldo_awal',
        ];
    }
}
