<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieTurbidite extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_turbidites';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
