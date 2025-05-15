<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Mass assignment fields
   protected $fillable = [
    'user_id', 'kode_produk', 'nama', 'harga', 'kategori_id', 'stok', 'image'
];


    // Relasi Many-to-One: Produk memiliki satu Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
