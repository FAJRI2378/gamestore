<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property array<array-key, mixed> $items
 * @property string $total_harga
 * @property string $status
 * @property string|null $resi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $produks
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereResi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',       // diasumsikan array dengan struktur [produk_id => jumlah]
        'total_harga',
        'status',
        'resi',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    // Relasi Transaction ke User (many to one)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk mendapatkan produk-produk terkait berdasarkan items
 public function getProduksAttribute()
{
    if (!is_array($this->items)) {
        return collect();
    }

    $productIds = array_keys($this->items);

    return Produk::whereIn('id', $productIds)->get();
}

}
