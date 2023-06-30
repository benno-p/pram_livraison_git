<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $connection = 'pram';
    protected $table = 'communes';
     // protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['geom', 'insee', 'nom', 'wikipedia', 'surf_ha'];

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'groupe_commune');
    }


    // public function mares()
    // {
    // 	return $this->hasMany(Mare::class, 'insee', 'commune_insee');
    // }

}
