<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'id_review';
    protected $fillable = ['id_user', 'id_product', 'id_transaction_detail', 'rating', 'komentar', 'tanggapan_manager', 'reviewed_at'];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class, 'id_transaction_detail', 'id_transaction_detail');
    }
}
