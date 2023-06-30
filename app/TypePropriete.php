<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypePropriete extends Model
{
    protected $connection = 'pram';
    protected $table = 'type_proprietes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function mares()
    {
    	return $this->hasMany(Mare::class);
    }
}
