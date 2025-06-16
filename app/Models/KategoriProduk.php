<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
        'is_active',
    ];

    public $timestamps = true; // created_at dan updated_at aktif
}
