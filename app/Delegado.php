<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Delegado extends Model
{
    protected $fillable = [
        'clube_id',
        'name',
        'phone'
    ];

    public function clubes()
	{
		return $this->belongsTo(Clube::class, 'clube_id');
	}
}
