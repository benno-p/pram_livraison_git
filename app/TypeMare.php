<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeMare extends Model
{
    protected $connection = 'pram';
    protected $table = 'type_mares';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->hasMany(Fiche::class);
    }
}
