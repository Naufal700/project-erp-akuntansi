<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Produk([
            'kode_produk'       => $row['kode_produk'],
            'nama'              => $row['nama'],
            'satuan'            => $row['satuan'],
            'harga_beli'        => $row['harga_beli'],
            'harga_jual'        => $row['harga_jual'],
            'stok'              => $row['stok'],
            'stok_minimal'      => $row['stok_minimal'] ?? 0,
            'tipe_produk'       => $row['tipe_produk'],
            'tipe_stok'         => $row['tipe_stok'] ?? 'fifo',
            'id_kategori'       => $this->getKategoriId($row['kategori']),
            'id_supplier'       => $this->getSupplierId($row['supplier']),
            'barcode'           => $row['barcode'] ?? null,
            'lokasi_rak'        => $row['lokasi_rak'] ?? null,
            'keterangan'        => $row['keterangan'] ?? null,
            'saldo_awal_qty'    => $row['saldo_awal_qty'] ?? 0,
            'saldo_awal_harga'  => $row['saldo_awal_harga'] ?? 0,
            'is_active'         => isset($row['is_active']) ? (bool) $row['is_active'] : true,
        ]);
    }

    protected function getKategoriId($namaKategori)
    {
        if (!$namaKategori) return null;

        $kategori = KategoriProduk::firstOrCreate(['nama_kategori' => $namaKategori]);
        return $kategori->id;
    }

    protected function getSupplierId($namaSupplier)
    {
        if (!$namaSupplier) return null;

        $supplier = Supplier::firstOrCreate(['nama' => $namaSupplier]);
        return $supplier->id;
    }
}
