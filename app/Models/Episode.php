<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Episode extends Model {

    use SoftDeletes;

    protected $fillable = [
        'id_serie_tmdb',
        'name',
        'overview',
        'season_number',
        'episode_number',
        'air_date',
        'seen',
    ];

    // Add relationship to Serie
    public function serie(): BelongsTo {
        return $this->belongsTo(Serie::class, 'id_serie_tmdb', 'id_serie_tmdb');
    }
}
