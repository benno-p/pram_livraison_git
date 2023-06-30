<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieRegime extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_regimes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
