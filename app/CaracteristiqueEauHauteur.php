<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristiqueEauHauteur extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracteristique_eau_hauteurs';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
