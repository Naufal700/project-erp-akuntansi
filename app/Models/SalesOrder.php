<?php
// app/Models/SalesOrder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_order';
    protected $fillable = ['nomor_so', 'tanggal', 'id_customer', 'status', 'total'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'id_so');
    }
    public function salesOrderDetail()
    {
        return $this->hasMany(SalesOrderDetail::class, 'id_so');
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'id_so');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class, 'id_so', 'id');
        // 'sales_order_id' = nama kolom FK di sales_order_details ke sales_orders
        // 'id' = PK di sales_orders
    }
}
