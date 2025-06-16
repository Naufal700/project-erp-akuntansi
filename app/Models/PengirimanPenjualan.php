<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimanPenjualan extends Model
{
    protected $table = 'pengiriman_penjualan';

    protected $fillable = ['nomor_surat_jalan', 'tanggal', 'id_so', 'status_pengiriman'];

    protected $dates = ['tanggal'];

    // atau kalau Laravel versi baru, bisa pakai cast:
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'id_so');
    }
}
