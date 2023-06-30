<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonEeeFaune extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'taxon_eee_faunes';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom'];


    // public function eee_faunes()
    // {
    // 	return $this->hasMany(EeeFaune::class);
    // }

    protected $connection = 'pram';
    protected $table = 'tax_eee_faune_groupes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];


    public function eee_faunes()
    {
    	return $this->hasMany(EeeFaune::class, 'tax_eee_faune_groupe_id');
    }

}
