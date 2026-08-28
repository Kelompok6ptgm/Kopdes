<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id_product';
    protected $fillable = ['id_kopdes', 'id_category', 'nama_produk', 'deskripsi', 'harga', 'stok', 'gambar'];

    public function kopdes()
    {
        return $this->belongsTo(Kopdes::class, 'id_kopdes', 'id_kopdes');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_product', 'id_product');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'id_product', 'id_product');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_product', 'id_product');
    }
}
