<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EeeFaune extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'eee_faunes';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom', 'taxon_eee_faune_id'];

    // public function taxon_eee_faune()
    // {
    // 	return $this->belongsTo(TaxonEeeFaune::class);
    // }

    // public function fiches()
    // {
    // 	return $this->belongsToMany(Fiche::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }

    protected $connection = 'pram';
    protected $table = 'tax_eee_faune_especes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'cdnom', 'tax_eee_faune_groupe_id'];

    public function taxon_eee_faune()
    {
        return $this->belongsTo(TaxonEeeFaune::class, 'tax_eee_faune_groupe_id', 'tax_eee_faune_groupe_id');
    }

    public function fiches()
    {
        return $this->belongsToMany(Fiche::class, 'fiche_tax_eee_faune_espece', 'tax_eee_faune_espece_id', 'fiche_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }
}
