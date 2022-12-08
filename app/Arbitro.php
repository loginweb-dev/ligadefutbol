<?php

namespace App;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Arbitro extends Model
{
    use SoftDeletes;
}
