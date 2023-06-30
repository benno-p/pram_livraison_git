<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EeeFlore extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'eee_flores';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom', 'taxon_eee_flore_id'];

    // public function taxon_eee_flore()
    // {
    // 	return $this->belongsTo(TaxonEeeFlore::class);
    // }

    // public function fiches()
    // {
    // 	return $this->belongsToMany(Fiche::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();;
    // }

    protected $connection = 'pram';
    protected $table = 'tax_eee_flore_especes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'cdnom', 'tax_eee_flore_groupe_id'];

    public function taxon_eee_flore()
    {
        return $this->belongsTo(TaxonEeeFlore::class, 'tax_eee_flore_groupe_id', 'tax_eee_flore_groupe_id');
    }

    public function fiches()
    {
        return $this->belongsToMany(Fiche::class, 'fiche_tax_eee_flore_espece', 'tax_eee_flore_espece_id', 'fiche_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();;
    }

}
