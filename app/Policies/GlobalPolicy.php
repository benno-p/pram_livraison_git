<?php

namespace App\Policies;

use App\Utilisateur;
use Illuminate\Auth\Access\HandlesAuthorization;

class GlobalPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function developpeur(Utilisateur $utilisateur){
        return $utilisateur->hasRole('developpeur');
    }

    public function access_parametres(Utilisateur $utilisateur){
        return $utilisateur->hasRole('developpeur') || $utilisateur->hasRole('gestionnaire') || $utilisateur->hasRole('administrateur');
    }

    public function access_validation_mares_fiches(Utilisateur $utilisateur){
        return $utilisateur->hasRole('developpeur') || $utilisateur->hasRole('gestionnaire') || $utilisateur->hasRole('administrateur');
    }

    public function access_validation_compte(Utilisateur $utilisateur){
        return $utilisateur->hasRole('developpeur') || $utilisateur->hasRole('administrateur');
    }

    public function access_utilisateurs(Utilisateur $utilisateur){
        return $utilisateur->hasRole('developpeur') || $utilisateur->hasRole('administrateur');
    }


}
