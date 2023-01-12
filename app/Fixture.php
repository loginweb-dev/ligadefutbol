<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Fixture extends Model
{
    use SoftDeletes;
    protected $fillable = [
    'title',
    'user_id',
    'descansa_id',
    'temporada_id'
    ];
}
