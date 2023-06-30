<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observateur extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'observateurs';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'prenom', 'organisme', 'nom_source', 'utilisateur_id'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function fiches()
    {
        return $this->hasMany(Fiche::class);
    }

    public function mares()
    {
        return $this->hasMany(Mare::class);
    }
}
