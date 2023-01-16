<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clube;
use App\Jugadore;
use Carbon\Carbon;

class Transferencia extends Model
{
    protected $fillable = [
    'jugadore_id',
    'clube_id_origen',
    'clube_id_destino',
    'observacion',
    'precio'
    ];

    protected $appends=['published', 'fecha'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
	public function getFechaAttribute(){
		return date('d/m/Y', strtotime($this->attributes['created_at']));
	}

    public function club_origen()
	{
		return $this->belongsTo(Clube::class, 'clube_id_origen');
	}
    public function club_destino()
	{
		return $this->belongsTo(Clube::class, 'clube_id_destino');
	}
    public function jugador()
	{
		return $this->belongsTo(Jugadore::class, 'jugadore_id');
	}
    	
}
