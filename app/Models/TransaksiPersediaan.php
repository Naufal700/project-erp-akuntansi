<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPersediaan extends Model
{
    protected $table = 'transaksi_persediaan';

    protected $fillable = [
        'kode_produk',
        'tanggal',
        'jenis',
        'sumber',
        'id_ref',
        'qty',
        'harga',
        'qty_sisa',
    ];

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk', 'kode_produk');
    }

    // Scope untuk jenis tertentu
    public function scopeSaldoAwal($query)
    {
        return $query->where('jenis', 'saldo_awal');
    }

    public function scopePenerimaan($query)
    {
        return $query->where('jenis', 'penerimaan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    // FIFO: Ambil urutan barang masuk
    public static function ambilStokMasukFIFO($kode_produk)
    {
        return self::where('kode_produk', $kode_produk)
            ->whereIn('jenis', ['saldo_awal', 'penerimaan'])
            ->where('qty_sisa', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
