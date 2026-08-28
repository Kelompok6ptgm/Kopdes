<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['id_role', 'id_kopdes', 'nama', 'email', 'password', 'no_hp', 'kode_pos', 'alamat', 'foto', 'reset_requested'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function kopdes()
    {
        return $this->belongsTo(Kopdes::class, 'id_kopdes', 'id_kopdes');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_user', 'id_user');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_user', 'id_user');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_user', 'id_user');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'diverifikasi_oleh', 'id_user');
    }
}
