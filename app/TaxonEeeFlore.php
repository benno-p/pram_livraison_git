<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonEeeFlore extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'taxon_eee_flores';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom'];


    // public function eee_flores()
    // {
    // 	return $this->hasMany(EeeFlore::class);
    // }

    protected $connection = 'pram';
    protected $table = 'tax_eee_flore_groupes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];


    public function eee_flores()
    {
    	return $this->hasMany(EeeFlore::class, 'tax_eee_flore_groupe_id');
    }
}
