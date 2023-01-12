<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RelPartidoEvento extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'time',
        'partido_id',
        'jugador_id',
        'evento'
    ];
}
