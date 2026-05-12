<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id',
        'title',
        'duration_seconds',
        'track_number',
        'file_url',
        'play_count',
        'explicit',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'track_number'     => 'integer',
        'play_count'       => 'integer',
        'explicit'         => 'boolean',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function playHistories(): HasMany
    {
        return $this->hasMany(PlayHistory::class);
    }
}
