<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';
    protected $fillable = ['nomor_po', 'tanggal', 'id_supplier', 'status', 'total'];

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class, 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'id_po');
    }
    public function invoice()
    {
        return $this->hasOne(PembelianInvoice::class, 'id_po');
    }
    public function penerimaan()
    {
        return $this->hasMany(PenerimaanPembelian::class, 'id_po');
    }
}
