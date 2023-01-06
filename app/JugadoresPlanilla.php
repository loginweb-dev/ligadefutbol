<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Clube;
use App\RelPlanillaJugadore;
use App\Delegado;
use TCG\Voyager\Models\User;

class JugadoresPlanilla extends Model
{
	use SoftDeletes;
	protected $fillable = [
        'clube_id',
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
		'men_pagadas',
		'cant_jugs_deudores',
		'user_id'
    ];
    
    protected $appends=['published', 'fecha'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
	public function getFechaAttribute(){
		return date('d/m/Y H:m', strtotime($this->attributes['created_at']));
	}
	public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}	
    public function jugadores()
	{
		return $this->hasMany(RelPlanillaJugadore::class);
	}
	public function delegado()
	{
		return $this->belongsTo(Delegado::class, 'delegado_id');
	}
	public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }	

}