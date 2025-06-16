<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coa;

class MappingJurnal extends Model
{
    use HasFactory;

    protected $table = 'mapping_jurnal';

    protected $fillable = [
        'modul',
        'event',
        'kode_akun_debit',
        'kode_akun_kredit',
        'keterangan',
        'arus_kas_kelompok',
        'arus_kas_jenis',
        'arus_kas_keterangan',
    ];

    public function akunDebit()
    {
        return $this->belongsTo(Coa::class, 'kode_akun_debit', 'kode_akun');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Coa::class, 'kode_akun_kredit', 'kode_akun');
    }

    public function getNamaAkunDebitAttribute()
    {
        return $this->akunDebit ? $this->akunDebit->nama_akun : null;
    }

    public function getNamaAkunKreditAttribute()
    {
        return $this->akunKredit ? $this->akunKredit->nama_akun : null;
    }
}
