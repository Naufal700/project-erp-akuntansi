<?php
// app/Models/Supplier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    public $timestamps = false; // karena hanya ada created_at, tanpa updated_at

    protected $fillable = ['nama', 'alamat', 'telepon', 'email'];
}
