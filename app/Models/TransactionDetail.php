<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_detail';
    protected $primaryKey = 'id_transaction_detail';
    protected $fillable = ['id_transaction', 'id_product', 'quantity', 'harga_beli'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction', 'id_transaction');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'id_transaction_detail', 'id_transaction_detail');
    }
}
