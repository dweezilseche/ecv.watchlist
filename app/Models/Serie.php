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
        'episodes',
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

}
