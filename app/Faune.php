<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faune extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'faunes';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom', 'taxon_faune_id'];

    // public function taxon_faune()
    // {
    // 	return $this->belongsTo(TaxonFaune::class);
    // }

    // public function fiches()
    // {
    // 	return $this->belongsToMany(Fiche::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }

    protected $connection = 'pram';
    protected $table = 'tax_faune_especes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'cdnom', 'tax_faune_groupe_id'];


    public function taxon_faune()
    {
        return $this->belongsTo(TaxonFaune::class, 'tax_faune_groupe_id', 'tax_faune_groupe_id');
    }

    public function fiches()
    {
        return $this->belongsToMany(Fiche::class, 'fiche_tax_faune_espece', 'tax_faune_espece_id', 'fiche_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }


}
