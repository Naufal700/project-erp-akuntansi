<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianInvoiceDetail extends Model
{
    protected $table = 'pembelian_invoice_detail';

    protected $fillable = [
        'id_invoice',
        'id_produk',
        'qty',
        'harga',
        'diskon',
        'total',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function invoice()
    {
        return $this->belongsTo(PembelianInvoice::class, 'id_invoice');
    }
}
