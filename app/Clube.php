<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use TCG\Voyager\Models\User;

class Clube extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'presidente',
        'vicepresidente',
        'secre_general',
        'secre_hacienda',
        'vocal',
        'image',
        'puntos',
        'golesa',
        'golesc',
        'ta',
        'tr',
        'status',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jugadores(){
        return $this->hasMany(Jugadore::class);
    }    

    public function temporadas(){
        return $this->belongsTo(RelTemporadaClube::class, 'club_id');
    }    
}
