<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statut extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'statuts';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'nom_interne'];

    public function mares()
    {
        return $this->hasMany(Mare::class);
    }

    public function fiches()
    {
        return $this->hasMany(Fiche::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }
}
