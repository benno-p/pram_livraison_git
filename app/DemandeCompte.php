<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemandeCompte extends Model
{
    protected $connection = 'pram';
    protected $table = 'demande_comptes';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'prenom', 'email', 'telephone_fixe', 'telephone_portable', 'valide', 'organisme', 'motivation'];
}
