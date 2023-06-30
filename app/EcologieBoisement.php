<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EcologieBoisement extends Model
{
    protected $connection = 'pram';
    protected $table = 'ecologie_boisements';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
