<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use TCG\Voyager\Models\User;


class AsientoDetalle extends Model
{
    protected $fillable = [
        'asiento_id',
        'monto_pagado',
        'user_id'
    ];

    protected $appends=['published', 'fecha'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}
	public function getFechaAttribute(){
		return date('Y-m-d H:i', strtotime($this->attributes['created_at']));
	}
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
