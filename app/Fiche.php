<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fiche extends Model
{
    protected $connection = 'pram';
    protected $table = 'fiches';
    protected $primaryKey = 'id';
    protected $fillable = [
    	'utilisateur_id',
    	'mare_id',
        'type_mare_id',
    	'pompe_a_nez',
    	'situation_cloture_id',
    	'patrimoine_bati_autre',
    	'haie_contact_mare',
    	'caracteristique_forme_id',
    	'caracteristique_longueur',
    	'caracteristique_largeur',
    	'caracteristique_eau_hauteur_id',
    	'caracteristique_fond_mare_id',
    	'caracteristique_berge_id',
    	'caracteristique_curage_haut_berge',
    	'caracteristique_curage_haut_berge_texte',
    	'caracteristique_pietinement_id',
    	'hydrologie_regime_id',
    	'hydrologie_turbidite_id',
    	'couleur_specifique',
    	'couleur_specifique_autre',
    	'hydrologie_tampon_id',
    	'hydrologie_exutoire_id',
    	'ecologie_helophytes',
    	'ecologie_hydrophytes',
    	'ecologie_hydrophytes_non_enracines',
    	'ecologie_algues',
    	'ecologie_eau_libre',
    	'ecologie_fond_exonde',
    	'ecologie_boisement_id',
    	'ecologie_ombrage_id',
    	'intervenir_objectif',
        'stade_evolution_mare_id',
        'valide',
        'commentaire_validation',
        'remarque_generale',
        'date_observation',
        'statut_id',
        'observateur_id',
        'caracterisation_id',
        'mail_validateur_envoye',
        'hydrologie_presence_poisson_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function observateur()
    {
        return $this->belongsTo(Observateur::class);
    }

    public function mare()
    {
        return $this->belongsTo(Mare::class);
    }


    public function type_mare()
    {
        return $this->belongsTo(TypeMare::class);
    }

    public function usage_mares()
    {
    	return $this->belongsToMany(UsageMare::class);
    }

    public function usage_dechets()
    {
    	return $this->belongsToMany(UsageDechet::class);
    }

    public function situation_contextes()
    {
    	return $this->belongsToMany(SituationContexte::class);
    }

    public function situation_batis()
    {
    	return $this->belongsToMany(SituationBati::class);
    }

    public function situation_cloture()
    {
    	return $this->belongsTo(SituationCloture::class);
    }

    public function caracteristique_forme()
    {
    	return $this->belongsTo(CaracteristiqueForme::class);
    }

    public function caracteristique_eau_hauteur()
    {
    	return $this->belongsTo(CaracteristiqueEauHauteur::class);
    }

    public function caracteristique_fond_mare()
    {
    	return $this->belongsTo(CaracteristiqueFondMare::class);
    }

    public function caracteristique_berge()
    {
    	return $this->belongsTo(CaracteristiqueBerge::class);
    }

    public function caracteristique_pietinement()
    {
    	return $this->belongsTo(CaracteristiquePietinement::class);
    }

    public function hydrologie_reseaus()
    {
    	return $this->belongsToMany(HydrologieReseau::class);
    }

    public function hydrologie_alimentations()
    {
    	return $this->belongsToMany(HydrologieAlimentation::class);
    }

    public function hydrologie_regime()
    {
    	return $this->belongsTo(HydrologieRegime::class);
    }

    public function hydrologie_turbidite()
    {
    	return $this->belongsTo(HydrologieTurbidite::class);
    }

    public function hydrologie_tampon()
    {
    	return $this->belongsTo(HydrologieTampon::class);
    }

    public function hydrologie_exutoire()
    {
    	return $this->belongsTo(HydrologieExutoire::class);
    }

    public function hydrologie_presence_poisson()
    {
        return $this->belongsTo(HydrologiePresencePoisson::class);
    }

    public function ecologie_boisement()
    {
    	return $this->belongsTo(EcologieBoisement::class);
    }

    public function ecologie_ombrage()
    {
    	return $this->belongsTo(EcologieOmbrage::class);
    }

    public function interventions()
    {
    	return $this->belongsToMany(Intervention::class);
    }

    public function stade_evolution_mare()
    {
        return $this->belongsTo(StadeEvolutionMare::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }



    // public function faunes()
    // {
    //     return  $this->belongsToMany(Faune::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }
    public function faunes()
    {
        return  $this->belongsToMany(Faune::class, 'fiche_tax_faune_espece', 'fiche_id', 'tax_faune_espece_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }

    // public function flores()
    // {
    //     return $this->belongsToMany(Flore::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }
    public function flores()
    {
        return  $this->belongsToMany(Flore::class, 'fiche_tax_flore_espece', 'fiche_id', 'tax_flore_espece_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }


    // public function eee_faunes()
    // {
    //     return $this->belongsToMany(EeeFaune::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }
    public function eee_faunes()
    {
        return $this->belongsToMany(EeeFaune::class, 'fiche_tax_eee_faune_espece', 'fiche_id', 'tax_eee_faune_espece_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }

    // public function eee_flores()
    // {
    //     return $this->belongsToMany(EeeFlore::class)->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    // }
    public function eee_flores()
    {
        return $this->belongsToMany(EeeFlore::class, 'fiche_tax_eee_flore_espece', 'fiche_id', 'tax_eee_flore_espece_id')->withPivot('nombre_observe', 'pourcentage')->withTimestamps();
    }


    public function statut()
    {
        return $this->belongsTo(Statut::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'fiche_id');
    }


    public function scopeMesFichesData($query)
    {
        return $query
        ->leftjoin('mares', 'mares.id', '=', 'fiches.mare_id')
        ->leftjoin('intercommunalites', 'intercommunalites.siren', '=', 'mares.intercommunalite_siren')
        ->leftjoin('communes', 'communes.insee', '=', 'mares.commune_insee')
        ->select(
            'fiches.id',
            'mare_id',
            'fiches.statut_id',
            'fiches.utilisateur_id',
            'fiches.observateur_id'
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
            'commentaires.utilisateur',
            'mare' => function($q){
                $q->select('id', 'nom', 'departement_code', 'intercommunalite_siren', 'commune_insee');
            },
            'mare.intercommunalite' => function($q){
                $q->select('id', 'siren', 'raison_soc');
            },
            'mare.commune' => function($q){
                $q->select('id', 'insee', 'nom');
            },
            'mare.departement' => function($q){
                $q->select('id', 'code_insee', 'nom');
            },
            'utilisateur' => function($q){
                $q->select('utilisateurs.id', 'nom', 'prenom');
            },
            'observateur' => function($q){
                $q->select('observateurs.id', 'nom', 'prenom', 'utilisateur_id');
            },
        ]);
    }
}
