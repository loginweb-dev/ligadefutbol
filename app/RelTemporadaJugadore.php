<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Temporada;
use App\Clube;
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
		return $this->belongsTo(Temporada::class, 'temporada_id');
	}
    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}
}
