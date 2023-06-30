<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracterisation extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracterisations';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'nom_interne', 'couleur', 'description'];

    public function mares()
    {
    	return $this->hasMany(Mare::class);
    }
}
