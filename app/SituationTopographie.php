<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituationTopographie extends Model
{
    protected $connection = 'pram';
    protected $table = 'situation_topographies';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function mares()
    {
    	return $this->hasMany(Mare::class);
    }
}
