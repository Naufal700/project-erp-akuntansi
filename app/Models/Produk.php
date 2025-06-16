<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimal',
        'tipe_produk',
        'tipe_stok',
        'id_kategori',
        'id_supplier',
        'barcode',
        'lokasi_rak',
        'keterangan',
        'is_active',
        'saldo_awal_qty',
        'saldo_awal_harga',
    ];

    public $timestamps = true;

    // Relasi ke kategori produk
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori');
    }

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    // Perhitungan saldo awal total
    public function getTotalSaldoAwalAttribute()
    {
        return $this->saldo_awal_qty * $this->saldo_awal_harga;
    }
    public function persediaan()
    {
        return $this->hasMany(TransaksiPersediaan::class, 'kode_produk', 'kode_produk');
    }
    public function transaksiPersediaan()
    {
        return $this->hasMany(TransaksiPersediaan::class, 'kode_produk', 'kode_produk');
    }
}
