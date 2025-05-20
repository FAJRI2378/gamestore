<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Transaction;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function pesanMasuk()
    {
        return $this->hasMany(Pesan::class, 'to_id');
    }

    public function pesanKeluar()
    {
        return $this->hasMany(Pesan::class, 'from_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaction::class); // <- singular, benar
    }
}
