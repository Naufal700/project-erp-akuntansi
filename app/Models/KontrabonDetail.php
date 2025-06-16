<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrabonDetail extends Model
{
    use HasFactory;

    protected $table = 'kontrabon_detail';
    protected $fillable = ['id_kontrabon', 'id_invoice'];

    public function kontrabon()
    {
        return $this->belongsTo(Kontrabon::class, 'id_kontrabon');
    }

    public function invoice()
    {
        return $this->belongsTo(PembelianInvoice::class, 'id_invoice');
    }
}
