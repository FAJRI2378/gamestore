<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Produk;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',
        'total_harga',
        'status',
        'resi',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

 public function getProdukAttribute()
{
    if (!is_array($this->items)) {
        return collect();
    }

    $productIds = array_keys($this->items); // Ambil kunci produk dari array items

    return Produk::whereIn('id', $productIds)->get();
}

}