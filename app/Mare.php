<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mare extends Model
{
    protected $connection = 'pram';
    protected $table = 'mares';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'geom',
        'x_l93',
        'y_l93',
        'lng',
        'lat',
        'nom',
        'organisme_origine',
        'id_origine',
        'auteur_origine',
        'annee_origine',
        'date_observation_origine',
        'departement_code',
        'intercommunalite_siren',
        'commune_insee',
        'utilisateur_id',
        'type_observateur_id',
        'type_observateur_autre',
        'type_propriete_id',
        'situation_topographie_id',
        'situation_topographie_texte',
        'caracterisation_id',
        'commentaire',
        'commentaire_validation',
        'valide',
        'code_ofb',
        'statut_id',
        'observateur_id',
        'mail_validateur_envoye',
        'statut_protection_id'
    ];

    protected $hidden = ["geom"];

    public function fiches()
    {
        return $this->hasMany(Fiche::class)->orderBy('id', 'asc');
    }

    public function latestFiche()
    {
        return $this->hasOne(Fiche::class)->latest();
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function observateur()
    {
        return $this->belongsTo(Observateur::class);
    }

    public function type_observateur()
    {
        return $this->belongsTo(TypeObservateur::class);
    }

    public function type_propriete()
    {
        return $this->belongsTo(TypePropriete::class);
    }

    public function situation_topographie()
    {
        return $this->belongsTo(SituationTopographie::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_code', 'code_insee');
    }

    public function intercommunalite()
    {
        return $this->belongsTo(Intercommunalite::class, 'intercommunalite_siren', 'siren');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_insee', 'insee');
    }

    public function caracterisation()
    {
        return $this->belongsTo(Caracterisation::class);
    }

    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'mare_id');
    }

    public function statut_protection()
    {
        return $this->belongsTo(StatutProtection::class);
    }


    public function scopeMesMaresData($query)
    {
        return $query
        ->leftjoin('intercommunalites', 'intercommunalites.siren', '=', 'mares.intercommunalite_siren')
        ->leftjoin('communes', 'communes.insee', '=', 'mares.commune_insee')
        ->select(
            'mares.id',
            'mares.nom',
            'mares.departement_code',
            'mares.intercommunalite_siren',
            'mares.commune_insee',
            'mares.valide',
            'mares.statut_id',
            'mares.utilisateur_id',
            'mares.observateur_id'
        )
        ->withCount([
            'commentaires as commentaires_utilisateurs_non_lus' => function($q){
                $q->where('utilisateur_vu', '=', false);
            },
            'commentaires as commentaires_validateurs_non_lus' => function($q){
                $q->where('validateur_vu', '=', false);
            }
        ])
        ->with([
            'statut',
            'commentaires' => function($q){
                $q->orderby('created_at', 'ASC');
            },
            'commentaires.utilisateur' => function($q){
                $q->select('id', 'nom', 'prenom');
            },
            'departement' => function($q){
                $q->select('id', 'code_insee', 'nom');
            },
            'intercommunalite' => function($q){
                $q->select('id', 'siren', 'raison_soc');
            },
            'commune' => function($q){
                $q->select('id', 'insee', 'nom');
            },
            'utilisateur' => function($q){
                $q->select('id', 'nom', 'prenom');
            },
            'observateur' => function($q){
                $q->select('id', 'nom', 'prenom');
            }
        ]);
        // ->distinct('mares.id');
    }



    public function scopeMareWithAllRelation($query, $all_fiches, $statut = false, $id_fiche = false)
    {
        return $query
        ->with([
            'statut',
            'utilisateur' => function($q){
                $q->select('utilisateurs.id', 'nom', 'prenom');
            },
            'observateur' => function($q){
                $q->select('observateurs.id', 'nom', 'prenom', 'utilisateur_id');
            },
            // 'fiches',
            'fiches.utilisateur' => function($q){
                $q->select('utilisateurs.id', 'nom', 'prenom');
            },
            'fiches.observateur' => function($q){
                $q->select('observateurs.id', 'nom', 'prenom', 'utilisateur_id');
            },
            'fiches.usage_mares',
            'fiches.usage_dechets',
            'fiches.situation_contextes',
            'fiches.situation_batis',
            'fiches.hydrologie_reseaus',
            'fiches.hydrologie_alimentations',
            'fiches.interventions',
            'fiches.faunes',
            'fiches.flores',
            'fiches.eee_faunes',
            'fiches.eee_flores',
            'fiches.stade_evolution_mare',
            'fiches.photos',
            'fiches.statut'
        ])
        ->when($all_fiches === true, function($query){
            $query->with('statut');
        })
        ->when($all_fiches === false && $id_fiche === false && !empty($statut), function($query) use($statut){
            $query
            ->with([
                'fiches' => function($req) use($statut){
                    $req->whereHas('statut', function($q) use($statut){
                        $q->where('nom_interne', '=', $statut);
                    });
                }
            ]);
        })
        ->when($all_fiches === false && $statut === false && !empty($id_fiche), function($query) use($id_fiche){
            $query
            ->with([
                'fiches' => function($req) use($id_fiche){
                    $req->where('id', $id_fiche);
                }
            ]);
        });
    }
}
