<?php

namespace App\Imports;

use App\Models\ProdukGudang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukGudangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return ProdukGudang::updateOrCreate(
            [
                'id_produk' => $row['id_produk'],
                'id_gudang' => $row['id_gudang']
            ],
            [
                'stok' => $row['stok'],
                'stok_minimal' => $row['stok_minimal']
            ]
        );
    }
}
