<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
    'phone'
    ];

    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}

}
