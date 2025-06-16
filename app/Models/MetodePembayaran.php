<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran'; // sesuaikan nama tabel

    protected $fillable = [
        'nama',
        'tipe',
        'kode_akun',
        'keterangan',
    ];

    public function coa()
    {
        // Relasi ke tabel coa berdasarkan kode_akun
        return $this->belongsTo(Coa::class, 'kode_akun', 'kode_akun');
    }
    public function pembayaranPenjualan()
    {
        return $this->hasMany(PembayaranPenjualan::class, 'id_metode_pembayaran');
    }
}
