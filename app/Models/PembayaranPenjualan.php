<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPenjualan extends Model
{
    protected $table = 'pembayaran_penjualan';  // nama tabel sesuai DB
    public $timestamps = false;

    protected $fillable = [
        'id_invoice',
        'tanggal',
        'id_metode_pembayaran',  // pastikan ini sudah ada
        'jumlah'
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'id_invoice');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran');
    }

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'id_so');
    }

    // Model SalesOrderDetail.php
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
