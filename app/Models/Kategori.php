<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Pastikan field 'nama' sesuai tabel database
    protected $fillable = ['nama'];

    // Relasi One-to-Many: Satu Kategori memiliki banyak Produk
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
