<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Clube;
use App\RelPlanillaJugadore;
class JugadoresPlanilla extends Model
{
	protected $fillable = [
        'club_id',
        'categoria_jugadores',
        'fecha_entrega',
        'veedor_id',
        'delegado_id',
        'deuda',
		'total',
		'observacion',
		'hora_entrega',
		'activo',
		'subtotal',
		'men_pagadas'
    ];
    
    use SoftDeletes;
    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'club_id');
	}	
    public function jugadores()
	{
		return $this->hasMany(RelPlanillaJugadore::class);
	}	

}