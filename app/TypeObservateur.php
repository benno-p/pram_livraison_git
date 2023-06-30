<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeObservateur extends Model
{
    protected $connection = 'pram';
    protected $table = 'type_observateurs';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function mares()
    {
    	return $this->hasMany(Mare::class);
    }
}
