<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristiqueForme extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracteristique_formes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
