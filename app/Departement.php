<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $connection = 'pram';
    protected $table = 'departements';
    protected $primaryKey = 'id';
    protected $fillable = ['geom', 'code_insee', 'nom', 'nuts3', 'wikipedia','surf_km2'];


    public function utilisateurs()
    {
    	return $this->belongsToMany(Utilisateur::class, 'departement_utilisateur');
    }

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'groupe_departement');
    }
}
