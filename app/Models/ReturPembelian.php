<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    use HasFactory;

    protected $table = 'retur_pembelian';

    protected $fillable = [
        'nomor_retur',
        'tanggal',
        'id_penerimaan',
        'id_supplier',
        'id_invoice',
        'keterangan',
        'total',
        'nilai_nota_kredit',
        'status',
        'created_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanPembelian::class, 'id_penerimaan');
    }

    public function invoice()
    {
        return $this->belongsTo(PembelianInvoice::class, 'id_invoice');
    }

    public function detail()
    {
        return $this->hasMany(ReturPembelianDetail::class, 'id_retur');
    }
    public function details()
    {
        return $this->hasMany(ReturPembelianDetail::class, 'id_retur_pembelian');
    }
}
