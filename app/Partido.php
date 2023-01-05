<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Partido extends Model
{
    use SoftDeletes;
    protected $fillable = [
        // 'description',
        'fecha',
        'hora',
        'planilla_a_id',
        'planilla_b_id',
        'veedor_id',
        // 'hora_comienzo_pt',
        // 'hora_comienzo_st',
        'categoria',
        'fixture_id'
        // 'juez_1',
        // 'juez_2',
        // 'juez_3',
        // 'juez_4'
    ];
}
