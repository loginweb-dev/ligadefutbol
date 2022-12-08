<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RelPartidoNomina extends Model
{
    
    use SoftDeletes;
	protected $fillable = [
        'partido_id',
        'nomina_id',
        'ta',
        'tr',
        'g1t',
        'g2t',
        'delegado_id',
        'result1t',
        'result2t',
        'evaluacion',
        'delegado_id',
        'jugador_id'
    ];

}
