<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $from_id
 * @property int $to_id
 * @property string $isi
 * @property int $dibaca
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereDibaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
