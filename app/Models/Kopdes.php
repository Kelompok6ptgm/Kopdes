<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kopdes extends Model
{
    use HasFactory;

    protected $table = 'kopdes';
    protected $primaryKey = 'id_kopdes';

    protected $fillable = [
        'nama_kopdes',
        'email',
        'no_telp',
        'alamat',
        'status',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_kopdes', 'id_kopdes');
    }
}
