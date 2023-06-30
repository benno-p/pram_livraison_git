<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituationBati extends Model
{
    protected $connection = 'pram';
    protected $table = 'situation_batis';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }
}
