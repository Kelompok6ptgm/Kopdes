<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id_category';
    protected $fillable = ['id_kopdes', 'nama_kategori'];

    public function kopdes()
    {
        return $this->belongsTo(Kopdes::class, 'id_kopdes', 'id_kopdes');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_category', 'id_category');
    }
}
