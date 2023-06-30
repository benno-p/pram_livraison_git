<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $connection = 'pram';
    protected $table = 'photos';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'chemin', 'fiche_id'];


    public function fiche()
    {
    	return $this->belongsTo(Fiche::class);
    }

}
