<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_detail';
    protected $fillable = ['id_po', 'id_produk', 'qty', 'harga', 'subtotal'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
