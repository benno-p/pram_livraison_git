<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $connection = 'tickets';
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $fillable = ['utilisateur_nom', 'utilisateur_prenom', 'utilisateur_email', 'application', 'type', 'objet', 'description', 'traite'];
}
