<?php

namespace App\Exports;

use App\Models\Gudang;
use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukGudangTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['1', '1', '100', '10'] // Contoh data: id_produk, id_gudang, stok, stok_minimal
        ]);
    }

    public function headings(): array
    {
        return ['id_produk', 'id_gudang', 'stok', 'stok_minimal'];
    }
}
