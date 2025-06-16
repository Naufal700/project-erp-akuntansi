<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianInvoice extends Model
{
    use HasFactory;

    protected $table = 'pembelian_invoice';

    protected $fillable = [
        'nomor_invoice',
        'tanggal',
        'nomor_faktur_pajak',        // ← Tambahkan ini
        'tanggal_faktur_pajak',      // ← Dan ini
        'id_po',
        'subtotal',
        'diskon',
        'ppn',
        'total',
        'status',
        'jatuh_tempo',
        'tanggal_pembayaran'
    ];


    public function penerimaan()
    {
        return $this->hasOne(\App\Models\PenerimaanPembelian::class, 'id_po', 'id_po');
    }

    public function details()
    {
        return $this->hasMany(\App\Models\PembelianInvoiceDetail::class, 'id_invoice');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'id_po');
    }
    public function supplier()
    {
        return $this->purchaseOrder?->supplier(); // indirect access
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranPembelian::class, 'id_kontrabon');
    }
    // PembelianInvoice.php

    public function kontrabonDetail()
    {
        return $this->hasOne(\App\Models\KontrabonDetail::class, 'id_invoice', 'id');
    }

    public function kontrabon()
    {
        return $this->hasOneThrough(
            \App\Models\Kontrabon::class,
            \App\Models\KontrabonDetail::class,
            'id_invoice',      // Foreign key di kontrabon_detail
            'id',              // Primary key di kontrabon
            'id',              // Local key di pembelian_invoice
            'id_kontrabon'     // Foreign key kontrabon_detail → kontrabon
        );
    }
    public function kontrabonDetails()
    {
        return $this->hasMany(\App\Models\KontrabonDetail::class, 'id_invoice', 'id');
    }

    public function kontrabons()
    {
        return $this->hasManyThrough(
            \App\Models\Kontrabon::class,
            \App\Models\KontrabonDetail::class,
            'id_invoice',      // FK di kontrabon_detail
            'id',              // PK di kontrabon
            'id',              // PK di invoice
            'id_kontrabon'     // FK kontrabon_detail ke kontrabon
        );
    }
    public function detail()
    {
        return $this->hasMany(PembelianInvoiceDetail::class, 'id_invoice', 'id');
    }
}
