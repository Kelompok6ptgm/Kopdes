<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_user', 'id_kopdes', 'kode_transaksi', 'total_harga', 'status_transaksi', 'alamat_pengiriman', 'catatan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kopdes()
    {
        return $this->belongsTo(Kopdes::class, 'id_kopdes', 'id_kopdes');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id_transaction');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_transaction', 'id_transaction');
    }
}
