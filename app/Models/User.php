<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
class User extends Authenticatable
{// app/Models/User.php

public function hasRole($role)
{
    return $this->role === $role; // Sesuaikan dengan logika role Anda
}


    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
'email',
'password',
'type'
];
protected $hidden = [
'password',
'remember_token',
];
protected $casts = [
'email_verified_at' => 'datetime',
];
protected function type(): Attribute
{
return new Attribute(
get: fn($value) =>  ["user", "admin"][$value],
);
}
}
