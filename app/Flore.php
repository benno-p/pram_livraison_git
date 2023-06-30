<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flore extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'flores';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom', 'taxon_flore_id'];

    // public function taxon_flore()
    // {
    // 	return $this->belongsTo(TaxonFlore::class);
    // }

    // public function fiches()
    // {
    // 	return $this->belongsToMany(Fiche::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }

    protected $connection = 'pram';
    protected $table = 'tax_flore_especes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'cdnom', 'tax_flore_groupe_id'];


    public function taxon_flore()
    {
        return $this->belongsTo(TaxonFlore::class, 'tax_flore_groupe_id', 'tax_flore_groupe_id');
    }

    public function fiches()
    {
        return $this->belongsToMany(Fiche::class, 'fiche_tax_flore_espece', 'tax_flore_espece_id', 'fiche_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }


}
