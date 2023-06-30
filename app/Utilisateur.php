<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $connection = 'pram';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'actif', 'role_id', 'groupe_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role){
        return $role === $this->role->nom_interne;
    }

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'departement_utilisateur');
    }

    public function fiches()
    {
        return $this->hasMany(Fiche::class);
    }

    public function mares()
    {
        return $this->hasMany(Mare::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function observateur()
    {
        return $this->hasOne(Observateur::class);
    }

    public function scopeUtilisateurGroupeId($query)
    {
        return $query
        ->with([
            'groupe' => function($q){
                $q->select('groupes.id');
            },
            'groupe.departements' => function($q){
                $q->select('departements.id', 'code_insee', 'nom');
            },
            'groupe.intercommunalites' => function($q){
                $q->select('intercommunalites.id', 'siren', 'raison_soc');
            },
            'groupe.communes' => function($q){
                $q->select('communes.id', 'insee', 'nom');
            },
            'departements' => function($q){
                $q->select('departements.id', 'code_insee', 'nom');
            }
        ]);
    }

}