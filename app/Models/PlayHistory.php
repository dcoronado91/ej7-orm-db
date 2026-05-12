<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'song_id',
        'played_at',
        'seconds_played',
        'completed',
    ];

    protected $casts = [
        'played_at'      => 'datetime',
        'seconds_played' => 'integer',
        'completed'      => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
