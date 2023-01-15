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
        'clube_id',
        'jugadore_id',
        'ta',
        'tr',
        'goles',
        'partidos'
    ];

    public function temporadas()
	{
		return $this->hasMany(Temporada::class);
	}
}
