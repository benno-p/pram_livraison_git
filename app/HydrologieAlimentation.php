<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieAlimentation extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_alimentations';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }

}
