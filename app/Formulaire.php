<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'formulaires';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'nom_interne'];

    public function groupes()
    {
        return $this->belongsToMany(Groupe::class);
    }
}
