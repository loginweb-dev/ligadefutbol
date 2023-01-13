<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Temporada;
class RelTemporadaJugadore extends Model
{
        
    use SoftDeletes;
    protected $fillable = [
        'temporada_id',
        'club_id',
        'ta',
        'tr',
        'golesa',
        'golesc',
        'partidos',
        'puntos'
    ];

    public function temporadas()
	{
		return $this->hasMany(Temporada::class);
	}
}
