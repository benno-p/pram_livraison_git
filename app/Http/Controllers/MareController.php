<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Mail\NouvelleMareNotification;
use App\Mail\NouvelleFicheNotification;
use Illuminate\Support\Facades\Mail;
use App\Traits\MareTrait;
use App\Mare;
use App\Utilisateur;
use App\Commune;
use App\Departement;
use App\Fiche;
use App\Observateur;
use App\Intercommunalite;
use Validator;
use Illuminate\Support\Facades\Route;
use App\Photo;
use App\Statut;


class MareController extends Controller
{

    use MareTrait;

    /*
     * 
     */
    public function index()
    {
        //
    }



    /*
     * Permet la création d'une mare depuis la carte
     */
    public function create()
    {
        $utilisateur = 
        Utilisateur::
        UtilisateurGroupeId()
        ->with([
            'role' => function($query){
                $query->select('id', 'nom_interne');
            }
        ])
        ->find(\Auth::id());
        $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('utilisateur'));
        return response()->json($data);
    }


   

    /*
     * Vérifie les champs pour la mare et la fiche (Voir app/Traits/MareTrait.php)
     * Si tout est ok, on enregistre la mare et la fiche
     * On envoi un mail au gestionnaire et ou a l'admin en fonction des departements (Voir app/Traits/MareTrait.php)
     */
    public function store(Request $request)
    {

        // Validation de la mare
        $validate = $this->validateMare($request);
        if($validate['error'] == true){
            return response()->json([
                'error' => $validate['error'], 
                'message' => $validate['message']
            ]);
        }

        // Validation de la fiche
        $validate = $this->validateFiche($request);
        if($validate['error'] == true){
            return response()->json([
                'error' => $validate['error'], 
                'message' => $validate['message']
            ]);
        }

        $statut = Statut::where('nom_interne', '=', 'en_attente_de_validation')->first();
        /*
         * Si l'utilisateur a le role gestionnaire
         * On regarde si la mare a été saisie dans son/ses departements
         * Si oui, on valide directement
         * Si non, même système de validation que les autres
         * Si administrateur, on valide directemment
         */
        if(\Auth::user()->hasRole('gestionnaire')){
            $user = Utilisateur::find(\Auth::id());
            $departements_user = $user->departements->pluck('code_insee')->toArray();

            if(in_array($request->departement_code, $departements_user)){
                $statut = Statut::where('nom_interne', '=', 'valider')->first();
            }
        }elseif(\Auth::user()->hasRole('administrateur')){
            $statut = Statut::where('nom_interne', '=', 'valider')->first();
        }

        // Création de la mare
        $mare = Mare::create([
            'nom' => $request->nom_mare,
            'type_observateur_id' => $request->type_observateur_id,
            'type_propriete_id' => $request->type_propriete_id, 
            'departement_code' => $request->departement_code,
            'geom' => $request->geom,
            'commune_insee' => $request->commune_insee,
            'intercommunalite_siren' => $request->intercommunalite_siren,
            'x_l93' => $request->x_lambert93,
            'y_l93' => $request->y_lambert93,
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'situation_topographie_id' => $request->situation_topographie_id,
            'utilisateur_id' => \Auth::id(),
            'code_ofb' => $request->code_ofb,
            'statut_id' => $statut->id,
            'observateur_id' => $request->observateur_id,
            'caracterisation_id' => $request->caracterisation_id ? $request->caracterisation_id : null,
            'mail_validateur_envoye' => false,
            'statut_protection_id' => $request->statut_protection_id ? $request->statut_protection_id : null
        ]);

        if($mare){
            $fiche = Fiche::create([
                'utilisateur_id' => \Auth::id(),
                'mare_id' => $mare->id,
                // Usage
                'type_mare_id' => $request->type_mare_id,
                'stade_evolution_mare_id' => $request->stade_evolution_mare_id,
                'pompe_a_nez' => $request->pompe_a_nez == 0 ? false : true,
                // Situation
                'situation_cloture_id' => $request->situation_cloture_id,
                'haie_contact_mare' => $request->haie_contact_mare == 0 ? false : true,
                // Caractéristiques
                'caracteristique_forme_id' => $request->caracteristique_forme_id,
                'caracteristique_longueur' => $request->caracteristique_longueur,
                'caracteristique_largeur' => $request->caracteristique_largeur,
                'caracteristique_eau_hauteur_id' => $request->caracteristique_eau_hauteur_id,
                'caracteristique_fond_mare_id' => $request->caracteristique_fond_mare_id,
                'caracteristique_berge_id' => $request->caracteristique_berge_id,
                'caracteristique_curage_haut_berge' => $request->caracteristique_curage_haut_berge == 0 ? false : true,
                'caracteristique_curage_haut_berge_texte' => $request->caracteristique_curage_haut_berge_texte,
                'caracteristique_pietinement_id' => $request->caracteristique_pietinement_id,
                // Hydrologie
                'hydrologie_regime_id' => $request->hydrologie_regime_id,
                'hydrologie_turbidite_id' => $request->hydrologie_turbidite_id,
                'couleur_specifique' => $request->couleur_specifique == 0 ? false : true,
                'couleur_specifique_autre' => $request->couleur_specifique_autre,
                'hydrologie_tampon_id' => $request->hydrologie_tampon_id,
                'hydrologie_exutoire_id' => $request->hydrologie_exutoire_id,
                'hydrologie_presence_poisson_id' => $request->hydrologie_presence_poisson_id,
                // Ecologie
                'ecologie_boisement_id' => $request->ecologie_boisement_id,
                'ecologie_ombrage_id' => $request->ecologie_ombrage_id,
                'ecologie_helophytes' => $request->ecologie_helophytes,
                'ecologie_hydrophytes' => $request->ecologie_hydrophytes,
                'ecologie_hydrophytes_non_enracines' => $request->ecologie_hydrophytes_non_enracines,
                'ecologie_algues' => $request->ecologie_algues,
                'ecologie_eau_libre' => $request->ecologie_eau_libre,
                'ecologie_fond_exonde' => $request->ecologie_fond_exonde,
                // Intervenir
                'intervenir_objectif' => $request->intervenir_objectif,
                'remarque_generale' => $request->remarque_generale,
                'date_observation' => $request->date_observation ? $request->date_observation : null,
                'statut_id' => $statut->id,
                'observateur_id' => $request->observateur_id,
                'caracterisation_id' => $request->caracterisation_id ? $request->caracterisation_id : null,
                'mail_validateur_envoye' => true
            ]);

            // many to many
            $fiche->usage_mares()->sync($request->usage_mare);
            $fiche->usage_dechets()->sync($request->usage_dechet);
            $fiche->situation_contextes()->sync($request->contexte);
            $fiche->situation_batis()->sync($request->bati);
            $fiche->hydrologie_reseaus()->sync($request->reseau);
            $fiche->hydrologie_alimentations()->sync($request->alimentation);
            $fiche->interventions()->sync($request->intervention);

            if($request->faune){
                $sync = [];
                foreach ($request->faune as $faune) {
                    $faune = json_decode($faune);
                    $sync[$faune->id] = ['nombre_observe' => $faune->nombre];
                }
                $fiche->faunes()->sync($sync);
            }

            if($request->flore){
                $sync = [];
                foreach ($request->flore as $flore) {
                    $flore = json_decode($flore);
                    $sync[$flore->id] = ['nombre_observe' => null];
                }
                $fiche->flores()->sync($sync);
            }

            if($request->eee_faune){
                $sync = [];
                foreach ($request->eee_faune as $eee_faune) {
                    $eee_faune = json_decode($eee_faune);
                    $sync[$eee_faune->id] = ['nombre_observe' => $eee_faune->nombre];
                }
                $fiche->eee_faunes()->sync($sync);
            }

            if($request->eee_flore){
                $sync = [];
                foreach ($request->eee_flore as $eee_flore) {
                    $eee_flore = json_decode($eee_flore);
                    $sync[$eee_flore->id] = ['pourcentage' => $eee_flore->pourcentage];
                }
                $fiche->eee_flores()->sync($sync);
            }


            // if($statut->nom_interne === 'en_attente_validation'){
            //     $emails = $this->checkEmailsGestionnairesAdministrateurs($request->departement_code);
            //     if(!empty($emails)){
            //         Mail::to($emails)->send(new NouvelleMareNotification($mare, $fiche));
            //     }
            // }
            
            if($request->hasFile('photo')){
                $path = $this->createPhoto($request, $mare, $fiche);

                if(!$path){
                    return response()->json([
                        'error' => false, 
                        'message' => [['La mare a été enregistrée mais un problème est survenu lors de l\'enregistrement de la photo. La mare sera visible une fois validée']]
                    ]);
                }
            }


            $error = false;

            $message = [['La mare a bien été créée. Elle sera visible une fois validée.']];
            if($statut->nom_interne === 'valider'){
                $message = [['La mare a bien été créée.']];
            }
            
            return response()->json(compact('error', 'message'));
        }


        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));

    }



    /*
     * Permet de consulter une mare depuis la carte
     */
    public function show($id)
    {
        $mare = Mare::MareWithAllRelation(false, 'valider')->find($id);

        if($mare){
            $utilisateur = 
            Utilisateur::UtilisateurGroupeId()
            ->with([
                'role' => function($query){
                    $query->select('id', 'nom_interne');
                }
            ])
            ->find(\Auth::id());
            $observateur = Observateur::where('utilisateur_id', '=', \Auth::id())->first();

            extract($this->checkGroupeUser());

            if(!empty($groupe_departements) && !empty($groupe_intercommunalites) && !empty($groupe_communes) && !empty($groupe_utilisateurs)){
                $utilisateur->groupe_departements = $groupe_departements;
                $utilisateur->groupe_intercommunalites = $groupe_intercommunalites;
                $utilisateur->groupe_communes = $groupe_communes;
                $utilisateur->groupe_utilisateurs = $groupe_utilisateurs;
            }

            $mare = $this->sanitizeMare($mare);
            $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('mare', 'utilisateur', 'observateur'));
            return response()->json($data);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }



    /*
     * Permet l'édition d'une mare dans mes mares et la consultation d'une mare dans mes fiches
     */
    public function edit($id)
    {
        $mare = Mare::MareWithAllRelation(true)->find($id);

        if($mare){
            $utilisateur = 
            Utilisateur::UtilisateurGroupeId()
            ->with('role', function($query){
                $query->select('id', 'nom_interne');
            })
            ->find(\Auth::id());
            $observateur = Observateur::where('utilisateur_id', '=', \Auth::id())->first();

            extract($this->checkGroupeUser());
            $utilisateur->groupe_departements = $groupe_departements;
            $utilisateur->groupe_intercommunalites = $groupe_intercommunalites;
            $utilisateur->groupe_communes = $groupe_communes;
            $utilisateur->groupe_utilisateurs = $groupe_utilisateurs;

            $mare_intercommunalite = Intercommunalite::where('siren', '=', $mare->intercommunalite_siren)->first();

            if($mare_intercommunalite){
                $mare->intercommunalite = $mare_intercommunalite->raison_soc;
            }

            $mare_commune = Commune::where('insee', '=', $mare->commune_insee)->first();
            if($mare_commune){
                $mare->commune = $mare_commune->nom;
            }

            $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('mare', 'utilisateur', 'observateur'));
            return response()->json($data);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }

    

    /*
     * Création d'une nouvelle fiche !
     */
    public function update(Request $request, $id)
    {
        // Validation de la fiche
        $validate = $this->validateFiche($request);
        if($validate['error'] == true){
            return response()->json([
                'error' => $validate['error'], 
                'message' => $validate['message']
            ]);
        }

        $mare = Mare::find($id);

        if($mare){
            $mare->update([
                'nom' => $request->nom_mare,
                'type_observateur_id' => $request->type_observateur_id,
                'type_propriete_id' => $request->type_propriete_id, 
                'situation_topographie_id' => $request->situation_topographie_id,
                'code_ofb' => $request->code_ofb,
                'statut_protection_id' => $request->statut_protection_id ? $request->statut_protection_id : null
            ]);


            $statut = Statut::where('nom_interne', '=', 'en_attente_de_validation')->first();
            /*
             * Si l'utilisateur a le role gestionnaire
             * On regarde si la mare a été saisie dans son/ses departements
             * Si oui, on valide directement
             * Si non, même système de validation que les autres
             * Si administrateur, on valide directemment
             */
            if(\Auth::user()->hasRole('gestionnaire')){
                $user = Utilisateur::find(\Auth::id());
                $departements_user = $user->departements->pluck('code_insee')->toArray();

                if(in_array($mare->departement_code, $departements_user)){
                    $statut = Statut::where('nom_interne', '=', 'valider')->first();
                    $mare->update([
                        'caracterisation_id' => $request->caracterisation_id ? $request->caracterisation_id : null
                    ]);
                }
            }elseif(\Auth::user()->hasRole('administrateur')){
                $statut = Statut::where('nom_interne', '=', 'valider')->first();
                $mare->update([
                    'caracterisation_id' => $request->caracterisation_id ? $request->caracterisation_id : null
                ]);
            }

            $fiche = Fiche::create([
                'utilisateur_id' => \Auth::id(),
                'mare_id' => $mare->id,
                // Usage
                'type_mare_id' => $request->type_mare_id,
                'stade_evolution_mare_id' => $request->stade_evolution_mare_id,
                'pompe_a_nez' => $request->pompe_a_nez == 0 ? false : true,
                // Situation
                'situation_cloture_id' => $request->situation_cloture_id,
                'haie_contact_mare' => $request->haie_contact_mare == 0 ? false : true,
                // Caractéristiques
                'caracteristique_forme_id' => $request->caracteristique_forme_id,
                'caracteristique_longueur' => $request->caracteristique_longueur,
                'caracteristique_largeur' => $request->caracteristique_largeur,
                'caracteristique_eau_hauteur_id' => $request->caracteristique_eau_hauteur_id,
                'caracteristique_fond_mare_id' => $request->caracteristique_fond_mare_id,
                'caracteristique_berge_id' => $request->caracteristique_berge_id,
                'caracteristique_curage_haut_berge' => $request->caracteristique_curage_haut_berge == 0 ? false : true,
                'caracteristique_curage_haut_berge_texte' => $request->caracteristique_curage_haut_berge_texte,
                'caracteristique_pietinement_id' => $request->caracteristique_pietinement_id,
                // Hydrologie
                'hydrologie_regime_id' => $request->hydrologie_regime_id,
                'hydrologie_turbidite_id' => $request->hydrologie_turbidite_id,
                'couleur_specifique' => $request->couleur_specifique == 0 ? false : true,
                'couleur_specifique_autre' => $request->couleur_specifique_autre,
                'hydrologie_tampon_id' => $request->hydrologie_tampon_id,
                'hydrologie_exutoire_id' => $request->hydrologie_exutoire_id,
                'hydrologie_presence_poisson_id' => $request->hydrologie_presence_poisson_id,
                // Ecologie
                'ecologie_boisement_id' => $request->ecologie_boisement_id,
                'ecologie_ombrage_id' => $request->ecologie_ombrage_id,
                'ecologie_helophytes' => $request->ecologie_helophytes,
                'ecologie_hydrophytes' => $request->ecologie_hydrophytes,
                'ecologie_hydrophytes_non_enracines' => $request->ecologie_hydrophytes_non_enracines,
                'ecologie_algues' => $request->ecologie_algues,
                'ecologie_eau_libre' => $request->ecologie_eau_libre,
                'ecologie_fond_exonde' => $request->ecologie_fond_exonde,
                // Intervenir
                'intervenir_objectif' => $request->intervenir_objectif,
                'remarque_generale' => $request->remarque_generale,
                'statut_id' => $statut->id,
                'observateur_id' => $request->observateur_id,
                'caracterisation_id' => $request->caracterisation_id ? $request->caracterisation_id : null,
                'date_observation' => $request->date_observation ? $request->date_observation : null
            ]);

            // many to many
            $fiche->usage_mares()->sync($request->usage_mare);
            $fiche->usage_dechets()->sync($request->usage_dechet);
            $fiche->situation_contextes()->sync($request->contexte);
            $fiche->situation_batis()->sync($request->bati);
            $fiche->hydrologie_reseaus()->sync($request->reseau);
            $fiche->hydrologie_alimentations()->sync($request->alimentation);
            $fiche->interventions()->sync($request->intervention);

            if($request->faune){
                $sync = [];
                foreach ($request->faune as $faune) {
                    $faune = json_decode($faune);
                    $sync[$faune->id] = ['nombre_observe' => $faune->nombre];
                }
                $fiche->faunes()->sync($sync);
            }

            if($request->flore){
                $sync = [];
                foreach ($request->flore as $flore) {
                    $flore = json_decode($flore);
                    $sync[$flore->id] = ['nombre_observe' => null];
                }
                $fiche->flores()->sync($sync);
            }

            if($request->eee_faune){
                $sync = [];
                foreach ($request->eee_faune as $eee_faune) {
                    $eee_faune = json_decode($eee_faune);
                    $sync[$eee_faune->id] = ['nombre_observe' => $eee_faune->nombre];
                }
                $fiche->eee_faunes()->sync($sync);
            }

            if($request->eee_flore){
                $sync = [];
                foreach ($request->eee_flore as $eee_flore) {
                    $eee_flore = json_decode($eee_flore);
                    $sync[$eee_flore->id] = ['pourcentage' => $eee_flore->pourcentage];
                }
                $fiche->eee_flores()->sync($sync);
            }

            // if($statut->nom_interne === 'en_attente_validation'){
            //     $emails = $this->checkEmailsGestionnairesAdministrateurs($mare->departement_code);
            //     if(!empty($emails)){
            //         Mail::to($emails)->send(new NouvelleFicheNotification($mare, $fiche));
            //     }
            // }

            if($request->hasFile('photo')){
                $path = $this->createPhoto($request, $mare, $fiche);

                if(!$path){
                    return response()->json([
                        'error' => false, 
                        'message' => [['La mare a été enregistrée mais un problème est survenu lors de l\'enregistrement de la photo. La mare sera visible une fois validée']]
                    ]);
                }
            }

            $error = false;
            $message = [['La fiche a bien été créée. Elle sera visible une fois validée.']];

            if($statut->nom_interne === 'valider'){
                $message = [['La fiche a bien été créée.']];
            }

            return response()->json(compact('error', 'message'));
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    
    /*
     * Récupère le siren, raison_soc, le nom de la commune, le numero de dep, les coordonnées en fonction de l'endroit cliqué sur la carte
     */
    public function findInfoMare(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $query = "SELECT DISTINCT(d.siren) AS siren, d.raison_soc, c.insee, c.nom, SUBSTR(c.insee, 1, 2) as dep, public.ST_x(public.ST_Transform(public.ST_SetSRID(public.ST_makepoint($longitude,$latitude),4326),2154)) As x_lambert93, 
        public.ST_Y(public.ST_Transform(public.ST_SetSRID(public.ST_makepoint($longitude,$latitude),4326),2154)) As y_lambert93, public.ST_Transform(public.ST_SetSRID(public.ST_Point($longitude,$latitude),4326),2154) AS geom from pram.communes AS c
        INNER JOIN pram.epcitab_ge AS d USING(insee)
        WHERE public.ST_Intersects(geom, public.ST_Transform(public.ST_SetSRID(public.ST_makepoint($longitude,$latitude),4326),2154))";

        $results = DB::select(DB::raw($query));
        $infos = [];

        foreach ($results as $result) {
            foreach ($result as $key => $value) {
                $infos[$key] = $value;
            }
        }

        return response()->json(compact('infos'));   
    }


    public function saveMareAfterDrag(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'departement_code' => 'required|numeric|exists:departements,code_insee',
            'geom' => 'required|string',
            'commune_insee' => 'required|numeric|exists:communes,insee',
            'intercommunalite_siren' => 'required|numeric|exists:intercommunalites,siren',
            'x_l93' => 'required',
            'y_l93' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'mare_id' => 'required|numeric|exists:mares,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $mare = Mare::find($request->mare_id);

        if($mare){
            $mare->update([
                'departement_code' => $request->departement_code,
                'geom' => $request->geom,
                'commune_insee' => $request->commune_insee,
                'intercommunalite_siren' => $request->intercommunalite_siren,
                'x_l93' => $request->x_l93,
                'y_l93' => $request->y_l93,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]); 
        }          
    }

}
