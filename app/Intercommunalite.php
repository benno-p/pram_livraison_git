<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intercommunalite extends Model
{
    protected $connection = 'pram';
    protected $table = 'intercommunalites';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'geom',
    	'id_geofla',
    	'statut',
    	'x_chf_lieu',
    	'y_chf_lieu',
    	'x_centroid',
    	'y_centroid',
    	'z_moyen',
    	'superficie',
    	'code_dept',
    	'nom_dept',
    	'code_reg',
    	'nom_region',
    	'insee_com',
    	'code_comm',
    	'nom_comm',
    	'population',
    	'code_cant',
    	'code_arr',
    	'dept',
    	'siren',
    	'raison_soc',
    	'nj',
    	'dep_com',
    	'insee',
    	'siren_memb',
    	'nom_membre',
    ];

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class, 'groupe_intercommunalite');
    }
}
