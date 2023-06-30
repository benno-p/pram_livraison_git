<?php
  
namespace App\Traits;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Mare;
use App\Utilisateur;
use App\CaracteristiqueBerge;
use App\CaracteristiqueEauHauteur;
use App\CaracteristiqueFondMare;
use App\CaracteristiqueForme;
use App\CaracteristiquePietinement;
use App\Commune;
use App\Departement;
use App\EcologieBoisement;
use App\EcologieOmbrage;
use App\Fiche;
use App\HydrologieAlimentation;
use App\HydrologieExutoire;
use App\HydrologieRegime;
use App\HydrologieReseau;
use App\HydrologieTampon;
use App\HydrologieTurbidite;
use App\HydrologiePresencePoisson;
use App\Intercommunalite;
use App\Intervention;
use App\SituationBati;
use App\SituationCloture;
use App\SituationContexte;
use App\SituationTopographie;
use App\StadeEvolutionMare;
use App\TypeMare;
use App\TypeObservateur;
use App\TypePropriete;
use App\UsageDechet;
use App\UsageMare;
use App\TaxonFaune;
use App\TaxonFlore;
use App\TaxonEeeFaune;
use App\TaxonEeeFlore;
use App\Faune;
use App\Flore;
use App\EeeFaune;
use App\EeeFlore;
use App\Formulaire;
use App\Photo;
use App\Observateur;
use App\Caracterisation;
use App\StatutProtection;

use Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
  
trait MareTrait {

    /*
     * Récupère toutes les listes déroulantes
     */
    public function getAllSelects()
    {
        if(!Cache::get('cache_selects')){
            $caracteristique_berges =  CaracteristiqueBerge::orderby('id', 'asc')->select('id', 'nom')->get();
            $caracteristique_eau_hauteurs = CaracteristiqueEauHauteur::orderby('id', 'asc')->select('id', 'nom')->get();
            $caracteristique_eau_hauteurs = $this->moveElFromArrayToOtherPlace($caracteristique_eau_hauteurs);
            $caracteristique_fond_mares = CaracteristiqueFondMare::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $caracteristique_formes = CaracteristiqueForme::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $caracteristique_pietinements = CaracteristiquePietinement::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();

            $ecologie_boisements = EcologieBoisement::select('id', 'nom')->get();
            $ecologie_ombrages = EcologieOmbrage::select('id', 'nom')->get();

            $hydrologie_alimentations = HydrologieAlimentation::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_exutoires = HydrologieExutoire::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_regimes = HydrologieRegime::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_reseaux = HydrologieReseau::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_tampons = HydrologieTampon::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_turbidites = HydrologieTurbidite::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $hydrologie_presence_poissons = HydrologiePresencePoisson::select('id', 'nom')->get();

            $interventions = Intervention::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();

            $situation_batis = SituationBati::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $situation_clotures = SituationCloture::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $situation_contextes = SituationContexte::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $situation_topographies = SituationTopographie::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();

            $stade_evolution_mares = StadeEvolutionMare::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom', 'chemin_image', 'descriptif')->get();

            $type_mares = TypeMare::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $type_observateurs = TypeObservateur::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $type_proprietes = TypePropriete::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();

            $usage_dechets = UsageDechet::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();
            $usage_mares = UsageMare::orderByRaw("id = 1 DESC, nom ASC")->select('id', 'nom')->get();

            $statut_protections = StatutProtection::select('id', 'nom')->orderby('nom', 'asc')->get();

            $selects = 
            compact(
                'caracteristique_berges',
                'caracteristique_eau_hauteurs',
                'caracteristique_fond_mares',
                'caracteristique_formes',
                'caracteristique_pietinements',
                'ecologie_boisements',
                'ecologie_ombrages',
                'hydrologie_alimentations',
                'hydrologie_exutoires',
                'hydrologie_regimes',
                'hydrologie_reseaux',
                'hydrologie_tampons',
                'hydrologie_turbidites',
                'hydrologie_presence_poissons',
                'interventions',
                'situation_batis',
                'situation_clotures',
                'situation_contextes',
                'situation_topographies',
                'stade_evolution_mares',
                'type_mares',
                'type_observateurs',
                'type_proprietes',
                'usage_dechets',
                'usage_mares',
                'statut_protections'
            );

            $seconds = 300; // 5 minutes
            Cache::put('cache_selects', $selects, $seconds);
        }else{
            $selects = Cache::get('cache_selects');
        }


        // Pas de cache pour les taxons car peut changer
        $selects['taxon_faunes'] = TaxonFaune::with('faunes')->orderby('nom', 'asc')->get();
        $selects['taxon_flores'] = TaxonFlore::with('flores')->orderby('nom', 'asc')->get();
        $selects['taxon_eee_faunes'] = TaxonEeeFaune::with('eee_faunes')->orderby('nom', 'asc')->get();
        $selects['taxon_eee_flores'] = TaxonEeeFlore::with('eee_flores')->orderby('nom', 'asc')->get();
        $selects['observateurs'] = Observateur::select('id', 'nom', 'prenom', 'utilisateur_id')->orderby('nom', 'asc')->selectRaw("CONCAT(nom,' ',prenom) as label")->get();
        
        $selects['formulaires'] = 
        Formulaire::
        with([
            'groupes' => function($query){
                $query->select('groupes.id', 'groupes.nom');
            }
        ])
        ->select('id', 'nom', 'nom_interne')
        ->get();

        $selects['caracterisations'] =  
        Caracterisation::
        where('nom', '!=', 'En attente de validation')
        ->orderby('nom', 'asc')
        ->get();

        return $selects;
    }


    /*
     * Permet de déplacer un élément dans un tableau à une autre place dans le tableau
     */
    private function moveElFromArrayToOtherPlace($collection, $new_place = 1, $old_place = null)
    {
        $array = [];

        if($old_place === null){
            $old_place = $collection->reverse()->keys()->first();
        }
        
        $diff = $old_place - $new_place;

        foreach ($collection as $key => $c) {
            if($key != $old_place && $key > $new_place){
                $array[$key+1] = $c;
            }elseif($key === $old_place){
                $array[$new_place+1] = $collection[$old_place-$diff];
                $array[$new_place] = $c;
            }else{
                $array[$key] = $c;
            }
        }
        ksort($array);
        return $array;
    }


    /*
     * Vérification du formulaire pour la mare
     */
    private function validateMare($request)
    {
        $response = [
            'error' => false,
            'message' => '',
        ];

        $validator = Validator::make($request->all(),[
            //Mare
            'nom_mare' => 'nullable|string|max:150',
            'type_observateur_id' => 'required|numeric|exists:type_observateurs,id',
            'type_propriete_id' => 'required|numeric|exists:type_proprietes,id',
            'departement_code' => 'required|numeric|exists:departements,code_insee',
            'geom' => 'required|string',
            'commune_insee' => 'required|numeric|exists:communes,insee',
            'intercommunalite_siren' => 'required|numeric|exists:intercommunalites,siren',
            'x_lambert93' => 'required',
            'y_lambert93' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'situation_topographie_id' => 'required|numeric|exists:situation_topographies,id',
            'code_ofb' => 'nullable|string|max:150|unique:mares,code_ofb',
            'observateur_id' => 'required|numeric|exists:observateurs,id',
            'caracterisation_id' => 'nullable|numeric|exists:caracterisations,id',
            'statut_protection_id' => 'nullable|numeric|exists:statut_protections,id'
        ]);

        if($validator->fails()) {
            $response = [
                'error' => true,
                'message' => $validator->messages(),
            ];
        }

        return $response;
    }



    /*
     * Vérification du formulaire pour la fiche
     */
    private function validateFiche($request)
    {
        $response = [
            'error' => false,
            'message' => '',
        ];

        $validator = Validator::make($request->all(),[
            // Usage
            'type_mare_id' => 'required|numeric|exists:type_mares,id',
            'stade_evolution_mare_id' => 'required|numeric|exists:stade_evolution_mares,id',
            'usage_mare' => 'required|array|min:1',
            'usage_mare.*' => 'required|numeric|exists:usage_mares,id',
            'pompe_a_nez' => 'required|boolean',
            'usage_dechet' => 'required|array|min:1',
            'usage_dechet.*' => 'required|numeric|exists:usage_dechets,id',
            // Situation
            'contexte' => 'nullable|array',
            'contexte.*' => 'nullable|numeric|exists:situation_contextes,id',
            'bati' => 'nullable|array',
            'bati.*' => 'nullable|numeric|exists:situation_batis,id',
            'situation_cloture_id' => 'nullable|numeric|exists:situation_clotures,id',
            'haie_contact_mare' => 'nullable|boolean',
            // Caracteristique
            'caracteristique_forme_id' => 'required|numeric|exists:caracteristique_formes,id',
            'caracteristique_eau_hauteur_id' => 'required|numeric|exists:caracteristique_eau_hauteurs,id',
            'caracteristique_longueur' => 'required|numeric',
            'caracteristique_largeur' => 'required|numeric',
            'caracteristique_fond_mare_id' => 'required|numeric|exists:caracteristique_fond_mares,id',
            'caracteristique_berge_id' => 'required|numeric|exists:caracteristique_berges,id',
            'caracteristique_curage_haut_berge' => 'required|boolean',
            'caracteristique_curage_haut_berge_texte' => 'nullable|string|max:255',
            'caracteristique_pietinement_id' => 'required|numeric|exists:caracteristique_pietinements,id',
            // Hydrologie
            'hydrologie_regime_id' => 'required|numeric|exists:hydrologie_regimes,id',
            'reseau' => 'nullable|array',
            'reseau.*' => 'nullable|numeric|exists:hydrologie_reseaus,id',
            'alimentation' => 'nullable|array',
            'alimentation.*' => 'nullable|numeric|exists:hydrologie_alimentations,id',
            'hydrologie_turbidite_id' => 'required|numeric|exists:hydrologie_turbidites,id',
            'couleur_specifique' => 'required|boolean',
            'couleur_specifique_autre' => 'nullable|string|max:255',
            'hydrologie_tampon_id' => 'nullable|numeric|exists:hydrologie_tampons,id',
            'hydrologie_exutoire_id' => 'required|numeric|exists:hydrologie_exutoires,id',
            // Ecologie
            'ecologie_boisement_id' => 'required|numeric|exists:ecologie_boisements,id',
            'ecologie_ombrage_id' => 'required|numeric|exists:ecologie_ombrages,id',
            'ecologie_helophytes' => 'nullable|numeric',
            'ecologie_hydrophytes' => 'nullable|numeric',
            'ecologie_hydrophytes_non_enracines' => 'nullable|numeric',
            'ecologie_algues' => 'nullable|numeric',
            'ecologie_eau_libre' => 'nullable|numeric',
            'ecologie_fond_exonde' => 'nullable|numeric',
            // Intervention
            'intervention' => 'nullable|array',
            'intervention.*' => 'nullable|numeric|exists:interventions,id',
            'intervenir_objectif' => 'nullable|string|max:255',
            //Photo
            'photo' => 'nullable|mimes:jpeg,JPEG,jpg,JPG,png,PNG|max:16000',
            'remarque_generale' => 'nullable|string',
            'date_observation' => 'nullable|date_format:Y-m-d',
            'observateur_id' => 'required|numeric|exists:observateurs,id',
            'caracterisation_id' => 'nullable|numeric|exists:caracterisations,id'
        ]);

        if($validator->fails()) {
            $response = [
                'error' => true,
                'message' => $validator->messages(),
            ];
        }


        return $response;
    }



    /*
     * Nettoie la commune et l'interco d'une mare pour l'affichage
     */
    private function sanitizeMare($mare){
        if($mare->intercommunalite){
            $intercommunalite = $mare->intercommunalite->raison_soc;
            unset($mare->intercommunalite);
            $mare->intercommunalite = $intercommunalite;
        }else{
            $mare->intercommunalite = '';
        }

        if($mare->commune){
            $commune = $mare->commune->nom;
            unset($mare->commune);
            $mare->commune = $commune;
        }else{
            $mare->commune = '';
        }

        return $mare;
    }



    /*
     * Renvoi les mails du/des gestionnaire(s) en fonction du departement de la mare
     * Si pas de gestionnaire, on check si il y a un/des administrateurs
     */
    private function checkEmailsGestionnairesAdministrateurs($departement)
    {
        $emails = [];

        $emails = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
                ->join('departement_utilisateur as du', 'du.utilisateur_id', '=', 'utilisateurs.id')
                ->join('departements as dep', 'dep.id', '=', 'du.departement_id')
                ->where([[
                    'r.nom_interne', '=', 'gestionnaire'],
                    ['dep.code_insee', '=', $departement]
                ])
                ->orwhere([[
                    'r.nom_interne', '=', 'administrateur'],
                    ['dep.code_insee', '=', $departement]
                ])
                ->pluck('utilisateurs.email')
                ->toArray();


        if(empty($emails)){
            $emails = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
                    ->where('r.nom_interne', '=', 'administrateur')
                    ->pluck('utilisateurs.email')
                    ->toArray();
        }

        return $emails;
    }


    /*
     * Regarde le groupe du user connecte
     */
    private function checkGroupeUser()
    {
        $groupe = null;
        $groupe_departements = [];
        $groupe_intercommunalites = [];
        $groupe_communes = [];
        $groupe_utilisateurs = [];

        if(\Auth::user() && \Auth::user()->groupe){
            $groupe = \Auth::user()->groupe;

            if($groupe->departements()->exists()){
                foreach ($groupe->departements as $dep) {
                    $groupe_departements[] = $dep->code_insee;
                }
            }elseif($groupe->intercommunalites()->exists()){
                foreach ($groupe->intercommunalites as $interco) {
                    $groupe_intercommunalites[] = $interco->siren;
                }
            }elseif($groupe->communes()->exists()){
                foreach ($groupe->communes as $com) {
                    $groupe_communes[] = $com->insee;
                }
            }elseif($groupe->id){
                $groupe_utilisateurs = Utilisateur::where('groupe_id', '=', $groupe->id)->pluck('id')->toArray();
            }
        }

        return compact('groupe', 'groupe_departements', 'groupe_intercommunalites', 'groupe_communes', 'groupe_utilisateurs');
    }



    public function checkDepartementsFromGroupe($groupe, $groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs)
    {
        $departements = [];
        if(!empty($groupe)){
            if(!empty($groupe_departements)){
                $departements = Departement::whereIn('code_insee', $groupe_departements)->get();
            }
            elseif(!empty($groupe_intercommunalites)){
                $departements_codes = Intercommunalite::whereIn('id', $groupe_intercommunalites)->pluck('code_dept')->toArray();
                $departements = Departement::whereIn('code_insee', $departements_codes)->get();
            }
            elseif(!empty($groupe_communes)){
                $departements_codes = [];
                foreach ($groupe_communes as $commune) {
                    $departements_codes[] = substr($commune, 0, 2);
                }
                $departements = Departement::whereIn('code_insee', $departements_codes)->get();   
            }
            elseif(!empty($groupe_utilisateurs)){
                $departements = [];
            }
        }
        return $departements;
    }

    

    /*
     * Permet la suppressiond d'une photo lors de la suppression d'une mare ou d'une fiche
     */
    public function removePhoto($file, $path, $fiche, $mare)
    {
        $photo = public_path($path.$file);
        if(file_exists($photo)){
            unlink($photo);
        }

        $fiche_folder = public_path('upload_photo_fiche/'.$mare.'/'.$fiche);
        if(file_exists($fiche_folder) && $this->is_dir_empty($fiche_folder)){
            rmdir($fiche_folder);
        }

        $mare_folder = public_path('upload_photo_fiche/'.$mare);
        if(file_exists($mare_folder) && $this->is_dir_empty($mare_folder)){
            rmdir($mare_folder);
        }
    }


    /*
     * Vérifie si le dossier est vide pour la suppression de celui-ci
     */
    public function is_dir_empty($dir) {
        if (!is_readable($dir)) return null; 
        return (count(scandir($dir)) == 2);
    }



    /*
     * Création de la photo lors de l'upload
     */
    public function createPhoto($request, $mare, $fiche)
    {
        $photo = $request->file('photo');
        // $filename = $photo->getClientOriginalName();

        $random = str_random(40);
        $guessExtension = $request->file('photo')->guessExtension();
        $filename = $random.'.'.$guessExtension;



        $image_resize = Image::make($photo->getRealPath());
        $image_resize->resize(800, 532);
        // $path = $image_resize->store($mare->id.'/'.$fiche->id, 'upload_photo_fiche');
        $path = public_path('upload_photo_fiche/'.$mare->id.'/'.$fiche->id.'/');



        if(!file_exists($path)){
            mkdir($path,0777,true);
        }

        $path = 'upload_photo_fiche/'.$mare->id.'/'.$fiche->id.'/';

        $image_resize->save($path.$filename);
        $photo_save = Photo::create([
            'nom' => $filename,
            'chemin' => $path,
            'fiche_id' => $fiche->id,
        ]);

        if($photo_save && $path){
            return $path;
        }

        return false;

        // if($request->hasFile('image')) {
        //     $image       = $request->file('image');
        //     $filename    = $image->getClientOriginalName();

        //     $image_resize = Image::make($image->getRealPath());              
        //     $image_resize->resize(300, 300);
        //     $image_resize->save(public_path('images/ServiceImages/' .$filename));
        // }



        // if($request->photo){
        //     $photo = $request->file('photo');
        //     $path = $photo->store($mare->id.'/'.$fiche->id, 'upload_photo_fiche');

        //     Image::make(storage_path($path))->resize(800, 532)->save();

        //     $photo_save = Photo::create([
        //         'chemin' => $path,
        //         'fiche_id' => $fiche->id,
        //     ]);
        // }
    }
} 