<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model {

    use SoftDeletes;

    protected $fillable = [
        'id_serie_tmdb',
        'name',
        'image',
        'director',
        'backdrop',
        'overview',
        'seasons',
        'episode_count', // Renamed from 'episodes' to 'episode_count'
        'all_seasons',
    ];
    
    protected $casts = [
        'all_seasons' => 'array',
    ];

    public function genres(): BelongsToMany {
        return $this->belongsToMany(Genre::class);
    }

    public function actors(): BelongsToMany {
        return $this->belongsToMany(Actor::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'id_serie_tmdb', 'id_serie_tmdb');
    }
}
