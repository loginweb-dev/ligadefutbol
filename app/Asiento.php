<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Jugadore;
use App\AsientoDetalle;

class Asiento extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'tipo',
        'monto',
        'editor_id',
        'planilla_id',
        // 'clube_id',
        'jugador_id',
        'observacion',
        'estado',
        'monto_pagado',
        'monto_restante',
        'cat_asiento_id'
    ];

    protected $appends=['published', 'fecha'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
	public function getFechaAttribute(){
		return date('d-m-Y H:m', strtotime($this->attributes['created_at']));
	}

    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}
    public function jugadores()
	{
		return $this->belongsTo(Jugadore::class, 'jugador_id');
	}
    public function categorias(){
        return $this->belongsTo(AsientoCategoria::class, 'cat_asiento_id');
    }
    public function detalles()
	{
		return $this->hasMany(AsientoDetalle::class);
	}			
}
