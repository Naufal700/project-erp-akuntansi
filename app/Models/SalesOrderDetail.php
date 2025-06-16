<?php
// app/Models/SalesOrderDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    protected $table = 'sales_order_detail';
    protected $fillable = ['id_so', 'id_produk', 'qty', 'harga', 'diskon', 'subtotal'];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'id_so');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
