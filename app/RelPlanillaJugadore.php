<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RelPlanillaJugadore extends Model
{
    use SoftDeletes;

	protected $fillable = [
        'planilla_id',
        'jugador_id',
        'titular',
        'mensualidad'
    ];

    public function jugador()
	{
		return $this->belongsTo(Jugadore::class, 'jugador_id');
	}	
}
