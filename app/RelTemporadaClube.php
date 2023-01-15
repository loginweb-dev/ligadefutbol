<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RelTemporadaClube extends Model
{
    
    use SoftDeletes;
    protected $fillable = [
        'temporada_id',
        'club_id',
        'ta',
        'tr',
        'golesa',
        'partidos',
        'puntos',
        'golesc',
        'descansos'
    ];
}
