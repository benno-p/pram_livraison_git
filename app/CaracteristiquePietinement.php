<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristiquePietinement extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracteristique_pietinements';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
