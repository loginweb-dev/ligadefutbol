<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Asiento extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'tipo',
        'detalle',
        'monto',
        'editor_id',
        'planilla_id',
        'clube_id',
        'jugador_id',
        'observacion',
        'estado',
        'monto_pagado',
        'monto_restante'
    ];

    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}
    public function jugadores()
	{
		return $this->belongsTo(Jugadore::class, 'jugador_id');
	}		
}
