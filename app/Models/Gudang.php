<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'gudang';

    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'alamat',
        'keterangan',
        'is_active'
    ];

    public function stokProduk()
    {
        return $this->hasMany(ProdukGudang::class, 'id_gudang');
    }
}
