<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HydrologieReseau extends Model
{
    protected $connection = 'pram';
    protected $table = 'hydrologie_reseaus';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }

}
