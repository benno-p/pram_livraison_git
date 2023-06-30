<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieTampon extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_tampons';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }

}
