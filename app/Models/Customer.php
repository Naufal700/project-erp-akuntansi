<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email'
    ];
}
