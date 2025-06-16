<?php
// app/Exports/ProdukTemplateExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ProdukTemplateExport implements FromCollection
{
    public function collection()
    {
        return new Collection([
            [
                'kode_produk',
                'nama',
                'satuan',
                'harga_beli',
                'harga_jual',
                'stok',
                'stok_minimal',
                'tipe_produk',
                'tipe_stok',
                'kategori',
                'supplier',
                'barcode',
                'lokasi_rak',
                'keterangan',
                'saldo_awal_qty',
                'saldo_awal_harga',
                'is_active'
            ],
            [
                'P001',
                'Contoh Produk',
                'pcs',
                10000,
                15000,
                100,
                10,
                'barang',
                'fifo',
                'Umum',
                'PT Contoh Supplier',
                '1234567890123',
                'Rak A1',
                'Contoh produk untuk testing',
                100,
                10000,
                1
            ]
        ]);
    }
}
