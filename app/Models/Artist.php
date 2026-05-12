<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'image_url',
        'country',
        'monthly_listeners',
        'verified',
    ];

    protected $casts = [
        'verified'          => 'boolean',
        'monthly_listeners' => 'integer',
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }
}
