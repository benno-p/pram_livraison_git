<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonFaune extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'taxon_faunes';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom'];

    // public function faunes()
    // {
    // 	return $this->hasMany(Faune::class);
    // }

	protected $connection = 'pram';
    protected $table = 'tax_faune_groupes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function faunes()
    {
    	return $this->hasMany(Faune::class, 'tax_faune_groupe_id');
    }


}
