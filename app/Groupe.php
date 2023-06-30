<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'groupes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class);
    }

    public function communes()
    {
        return $this->belongsToMany(Commune::class, 'groupe_commune');
    }

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'groupe_departement');
    }

    public function intercommunalites()
    {
        return $this->belongsToMany(Intercommunalite::class, 'groupe_intercommunalite');
    }

    public function formulaires()
    {
        return $this->belongsToMany(Formulaire::class);
    }
}