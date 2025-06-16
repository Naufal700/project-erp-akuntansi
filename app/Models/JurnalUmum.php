<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';

    protected $fillable = [
        'tanggal',
        'kode_akun',
        'nominal_debit',
        'nominal_kredit',
        'keterangan',
        'ref',
        'ref_id',
        'modul',
    ];

    public $timestamps = true;

    // Relasi ke COA (akun)
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'kode_akun', 'kode_akun');
    }
}
