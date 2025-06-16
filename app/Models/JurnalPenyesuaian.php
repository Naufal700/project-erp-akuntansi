<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalPenyesuaian extends Model
{
    protected $table = 'jurnal_penyesuaian';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'tanggal',
        'kode_akun',
        'keterangan',
        'nominal_debit',
        'nominal_kredit',
    ];

    public function akun()
    {
        return $this->belongsTo(Coa::class, 'kode_akun', 'kode_akun');
    }
}
