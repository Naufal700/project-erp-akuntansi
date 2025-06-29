<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Supplier([
            'nama' => $row['nama'],
            'alamat' => $row['alamat'] ?? null,
            'telepon' => $row['telepon'] ?? null,
            'email' => $row['email'] ?? null,
        ]);
    }
}
