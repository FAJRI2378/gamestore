<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model Produk
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $kode_produk
 * @property string $nama
 * @property string $harga
 * @property int $stok
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $image
 * @property int $kategori_id
 * @property string|null $game
 * @property-read \App\Models\Kategori $kategori
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereGame($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereKategoriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereKodeProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereUserId($value)
 * @mixin \Eloquent
 */
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
