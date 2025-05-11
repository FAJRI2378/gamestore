<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $fillable = [
        'from_id', 'to_id', 'isi', 'dibaca',
    ];

    // Relasi ke pengguna pengirim (sender)
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    // Relasi ke pengguna penerima (recipient)
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
