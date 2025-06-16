<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrabon extends Model
{
    use HasFactory;

    protected $table = 'kontrabon';
    protected $fillable = [
        'nomor_kontrabon',
        'tanggal',
        'jatuh_tempo',
        'id_supplier',
        'total',
        'keterangan',
        'status',
        'tanggal_pembayaran'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(KontrabonDetail::class, 'id_kontrabon');
    }
    public function pembayaran()
    {
        return $this->hasMany(PembayaranPembelian::class, 'id_kontrabon');
    }
    public function pembelianInvoice()
    {
        return $this->belongsTo(PembelianInvoice::class, 'id_invoice');
    }
}
