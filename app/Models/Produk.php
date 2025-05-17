<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Produk
class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['kode_produk', 'nama', 'harga', 'kategori_id', 'stok', 'game', 'image', 'user_id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
