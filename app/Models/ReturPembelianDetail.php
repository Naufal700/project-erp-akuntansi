<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'retur_pembelian_detail';

    public $timestamps = false; // kalau kamu pakai created_at, set true

    protected $fillable = [
        'id_retur',
        'id_produk',
        'qty_retur',
        'harga_satuan',
        'keterangan',
    ];

    protected $appends = ['subtotal'];

    public function retur()
    {
        return $this->belongsTo(ReturPembelian::class, 'id_retur');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function getSubtotalAttribute()
    {
        return $this->qty_retur * $this->harga_satuan;
    }
}
