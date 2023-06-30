<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituationCloture extends Model
{
    protected $connection = 'pram';
    protected $table = 'situation_clotures';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
