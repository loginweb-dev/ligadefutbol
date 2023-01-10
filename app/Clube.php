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
        'puntos',
        'golesa',
        'golesc',
        'ta',
        'tr'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jugadores(){
        return $this->hasMany(Jugadore::class);
    }    
}
