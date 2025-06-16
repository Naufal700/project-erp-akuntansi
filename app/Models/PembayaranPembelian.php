<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPembelian extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pembelian';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_kontrabon',
        'tanggal',
        'metode',
        'jumlah',
        'created_at'
    ];

    public function kontrabon()
    {
        return $this->belongsTo(Kontrabon::class, 'id_kontrabon');
    }
}
