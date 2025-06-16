<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_pembelian_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_penerimaan',
        'id_produk',
        'qty_diterima',
    ];

    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanPembelian::class, 'id_penerimaan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function purchaseOrderDetail()
    {
        return $this->hasOne(PurchaseOrderDetail::class, 'id_produk', 'id_produk')
            ->where('id_po', $this->penerimaan->id_po ?? null);
    }
}
