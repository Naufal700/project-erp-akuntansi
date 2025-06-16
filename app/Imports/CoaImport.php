<?php

namespace App\Imports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Coa([
            'kode_akun'   => $row['kode_akun'],
            'nama_akun'   => $row['nama_akun'],
            'tipe_akun'   => $row['tipe_akun'],
            'parent_kode' => $row['parent_kode'] ?? null,
            'level'       => $row['level'] ?? null,
            'saldo_awal'  => $row['saldo_awal'] ?? 0,
        ]);
    }
}
