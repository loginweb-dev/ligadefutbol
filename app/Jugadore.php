<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Transferencia;

class Jugadore extends Model
{
    use SoftDeletes;
    protected $fillable = [
    'name',
    'polera',
    'edad',
    'nacido',
    'jug_categoria',
    'clube_id',
    'foto',
    'color_carnet',
    'phone',
    'status'
    ];


    protected $appends=['published', 'fecha', 'nacimiento'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
	public function getFechaAttribute(){
		return date('d-m-Y', strtotime($this->attributes['created_at']));
	}
    public function getNacimientoAttribute(){
		return date('d-m-Y', strtotime($this->attributes['nacido']));
	}


    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}
    public function transferencias()
	{
		return $this->hasMany(Transferencia::class);
	}
    

}
