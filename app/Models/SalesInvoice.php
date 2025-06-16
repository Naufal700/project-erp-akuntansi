<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $table = 'sales_invoice';

    protected $fillable = [
        'nomor_invoice',
        'tanggal',
        'id_so',
        'total',
        'ppn',
        'status',
        'jatuh_tempo',
    ];
    protected $casts = [
        'tanggal' => 'date',  // atau 'datetime' kalau ada waktu
        'jatuh_tempo' => 'date',
    ];


    // Relasi ke sales order
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'id_so');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer'); // pastikan foreign key benar
    }
    public function pembayaranPenjualan()
    {
        return $this->hasMany(PembayaranPenjualan::class, 'id_invoice', 'id');
    }
    public function salesOrderDetail()
    {
        return $this->hasOneThrough(
            SalesOrderDetail::class,
            SalesOrder::class,
            'id',            // Foreign key di sales_orders
            'id_so', // Foreign key di sales_order_detail
            'id_so', // Foreign key di sales_invoice
            'id'             // Local key di sales_order
        );
    }
}
