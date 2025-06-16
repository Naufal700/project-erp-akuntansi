<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPembelian extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_pembelian';

    protected $fillable = [
        'nomor_penerimaan',
        'tanggal',
        'id_po',
        'status',
    ];
    public $timestamps = false;

    public function purchaseOrder()
    {
        return $this->belongsTo(\App\Models\PurchaseOrder::class, 'id_po');
    }

    public function getProdukPO($id)
    {
        $details = PurchaseOrderDetail::with('produk')
            ->where('id_po', $id)
            ->get();

        return response()->json($details);
    }
    public function detail()
    {
        return $this->hasMany(PenerimaanPembelianDetail::class, 'id_penerimaan');
    }
    public function detailPenerimaan()
    {
        return $this->hasMany(PenerimaanPembelianDetail::class, 'id_penerimaan');
    }

    public function details()
    {
        return $this->hasMany(PenerimaanPembelianDetail::class, 'id_penerimaan');
    }

    public function invoice()
    {
        return $this->hasOne(PembelianInvoice::class, 'id_po', 'id_po');
    }
}
