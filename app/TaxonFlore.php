<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxonFlore extends Model
{
    // protected $connection = 'pram';
    // protected $table = 'taxon_flores';
    // protected $primaryKey = 'id';
    // protected $fillable = ['nom'];

    // public function flores()
    // {
    // 	return $this->hasMany(Flore::class);
    // }

	protected $connection = 'pram';
    protected $table = 'tax_flore_groupes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function flores()
    {
    	return $this->hasMany(Flore::class, 'tax_flore_groupe_id');
    }


}
