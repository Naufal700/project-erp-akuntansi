<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakturPajakMasukan extends Model
{
    protected $table = 'faktur_pajak_masukan';

    protected $fillable = [
        'id_invoice',
        'nomor_faktur_pajak',
        'tanggal_faktur_pajak',
        'nilai_dpp',
        'nilai_ppn',
    ];


    public function invoice()
    {
        return $this->belongsTo(PembelianInvoice::class, 'id_invoice');
    }
    public function getNamaSupplierAttribute()
    {
        return $this->invoice->penerimaan->purchaseOrder->supplier->nama ?? '-';
    }
}
