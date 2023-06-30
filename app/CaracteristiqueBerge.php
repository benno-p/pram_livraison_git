<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristiqueBerge extends Model
{
    protected $connection = 'pram';
    protected $table = 'caracteristique_berges';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
