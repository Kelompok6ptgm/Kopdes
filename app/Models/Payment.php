<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'id_payment';
    protected $fillable = ['id_transaction', 'jumlah_bayar', 'metode_pembayaran', 'bukti_pembayaran', 'status_pembayaran', 'diverifikasi_oleh', 'tanggal_verifikasi'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction', 'id_transaction');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh', 'id_user');
    }
}
