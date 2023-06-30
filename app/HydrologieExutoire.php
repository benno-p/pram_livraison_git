<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieExutoire extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_exutoires';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
