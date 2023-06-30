<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'commentaires_mares_fiches';
    protected $primaryKey = 'id';
    protected $fillable = ['mare_id', 'fiche_id', 'utilisateur_id', 'statut_id', 'message', 'utilisateur_vu', 'validateur_vu', 'utilisateur_send', 'validateur_send'];

    public function mare()
    {
        return $this->belongsTo(Mare::class, 'mare_id');
    }

    public function fiche()
    {
        return $this->belongsTo(Fiche::class, 'fiche_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

}
