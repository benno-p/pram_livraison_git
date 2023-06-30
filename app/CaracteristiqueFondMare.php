<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristiqueFondMare extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracteristique_fond_mares';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
