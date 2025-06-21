<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coa extends Model
{
    protected $table = 'coa';
    protected $primaryKey = 'kode_akun';
    public $incrementing = false;
    protected $keyType = 'string';    // tambahkan ini supaya primary key dianggap string
    public $timestamps = false;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        'parent_kode',
        'level',
        'saldo_awal_debit',
        'saldo_awal_kredit',
        'periode_saldo_awal',
    ];

    public static function getSaldoAkun($tanggal)
    {
        return DB::table('coa as c')
            ->leftJoin('jurnal_umum as j', 'c.kode_akun', '=', 'j.kode_akun')
            ->select(
                'c.kode_akun',
                'c.nama_akun',
                'c.tipe_akun',
                'c.parent_kode',
                'c.level',
                DB::raw('COALESCE(SUM(j.nominal_debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(j.nominal_kredit), 0) as total_kredit')
            )
            ->where(function ($query) use ($tanggal) {
                if ($tanggal) {
                    $query->where('j.tanggal', '<=', $tanggal);
                }
            })
            ->groupBy('c.kode_akun', 'c.nama_akun', 'c.tipe_akun', 'c.parent_kode', 'c.level')
            ->orderBy('c.kode_akun')
            ->get();
    }
    public function neracaSaldo(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $data_neraca = DB::table('coa as c')
            ->leftJoin(
                DB::raw("(SELECT kode_akun, 
                                   SUM(nominal_debit) as total_debit, 
                                   SUM(nominal_kredit) as total_kredit 
                            FROM jurnal_umum 
                            WHERE tanggal <= ? 
                            GROUP BY kode_akun) as ju"),
                'c.kode_akun',
                '=',
                'ju.kode_akun'
            )
            ->setBindings([$tanggal])  // binding untuk tanggal
            ->select(
                'c.kode_akun',
                'c.nama_akun',
                'c.saldo_awal_debit',
                'c.saldo_awal_kredit',
                'c.level',
                DB::raw('IFNULL(ju.total_debit, 0) as total_debit'),
                DB::raw('IFNULL(ju.total_kredit, 0) as total_kredit')
            )
            ->orderBy('c.kode_akun')
            ->get();

        return view('neraca_saldo', compact('data_neraca', 'tanggal'));
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'kode_akun', 'kode_akun');
    }
    public function akunDebit()
    {
        return $this->belongsTo(Coa::class, 'kode_akun_debit', 'kode_akun');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Coa::class, 'kode_akun_kredit', 'kode_akun');
    }
}
