<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukGudang extends Model
{
    use HasFactory;

    protected $table = 'produk_gudang';
    public $timestamps = false;
    protected $fillable = [
        'id_produk',
        'id_gudang',
        'stok',
        'stok_minimal',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }
}
