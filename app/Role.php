<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'pram';
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'nom_interne'];


    public function utilisateurs()
    {
    	return $this->hasMany(Utilisateur::class);
    }
}
