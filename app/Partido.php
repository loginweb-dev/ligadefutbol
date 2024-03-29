<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Partido extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'description',
        'fecha',
        'hora',
        'planilla_a_id',
        'planilla_b_id',
        'veedor_id',
        'hora_comienzo_pt',
        'hora_comienzo_st',
        'categoria',
        'fixture_id',
        'juez_1',
        'juez_2',
        'juez_3',
        'juez_4',
        'status',
        'ganador',
        'perdedor',
        'editor_id',
        'temporada_id',
        'obs_delegado_a',
        'obs_delegado_b',
        'cal_delegado_a',
        'cal_delegado_b'
    ];

    public function fixture()
	{
		return $this->belongsTo(Fixture::class, 'fixture_id');
	}
    public function planilla_a()
	{
		return $this->belongsTo(JugadoresPlanilla::class, 'planilla_a_id');        
	}
    public function planilla_b()
	{
		return $this->belongsTo(JugadoresPlanilla::class, 'planilla_b_id');
	}
    public function RelPartidoEvento()
	{
		return $this->belongsTo(RelPartidoEvento::class, 'partido_id');
	}
    public function estado()
	{
		return $this->belongsTo(PartidoEstado::class, 'status');
	}
    
}
