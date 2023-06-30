<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituationContexte extends Model
{
    protected $connection = 'pram';
    protected $table = 'situation_contextes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }
}
