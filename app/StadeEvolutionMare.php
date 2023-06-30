<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StadeEvolutionMare extends Model
{
    protected $connection = 'pram';
    protected $table = 'stade_evolution_mares';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'descriptif', 'chemin_image'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
