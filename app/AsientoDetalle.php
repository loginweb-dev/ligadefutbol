<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AsientoDetalle extends Model
{
    protected $fillable = [
        'asiento_id',
        'monto_pagado'
    ];
}
