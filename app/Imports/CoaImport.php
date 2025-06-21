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
            'kode_akun'          => $row['kode_akun'],
            'nama_akun'          => $row['nama_akun'],
            'tipe_akun'          => $row['tipe_akun'],
            'parent_kode'        => $row['parent_kode'] ?? null,
            'level'              => $row['level'] ?? null,
            'saldo_awal_debit'   => $row['saldo_awal_debit'] ?? 0,
            'saldo_awal_kredit'  => $row['saldo_awal_kredit'] ?? 0,
            'periode_saldo_awal' => $row['periode_saldo_awal'] ?? null,
        ]);
    }
}
