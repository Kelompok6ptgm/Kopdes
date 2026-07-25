<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kopdes extends Model
{
    protected $table = 'kopdes';
    protected $primaryKey = 'id_kopdes';
    protected $fillable = ['nama_kopdes', 'alamat', 'kode_pos', 'provinsi', 'no_hp', 'status'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_kopdes', 'id_kopdes');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'id_kopdes', 'id_kopdes');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_kopdes', 'id_kopdes');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_kopdes', 'id_kopdes');
    }
}
