<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Traits\MareTrait;
use App\Http\Traits\ErrorTrait;

use App\Mare;
use App\Fiche;
use App\Statut;
use App\Utilisateur;
use App\Observateur;
use App\Commentaire;
use App\Formulaire;

use Validator;


class MesMareController extends Controller
{
    use MareTrait;
    use ErrorTrait;

    /*
     * Affiches les mares dans toutes mes mares ou mares en attente en fonction de la route
     */
    public function index(Request $request)
    {
        $types = [];
        $groupe = null;
        $types_selected = null;
        $statuts = Statut::all();

        $utilisateur = 
        Utilisateur::
        UtilisateurGroupeId()
        ->with([
            'role' => function($query){
                $query->select('id', 'nom_interne');
            }
        ])
        ->find(\Auth::id());

        if(\Auth::user()->groupe){
            $groupe = \Auth::user()->groupe;

            if(Route::currentRouteName() === 'mes_mares.index'){
                $default = 'MES MARES';

                if($request->session()->get('filtres_mes_mares')){
                    $types_selected = $request->session()->get('filtres_mes_mares');
                }else{
                    $types_selected = ['nom' => $default, 'id' => 0];
                    $request->session()->put('filtres_mes_mares', $types_selected);
                }
            }else{
                $default = 'MES FICHES';

                if($request->session()->get('filtres_mes_fiches')){
                    $types_selected = $request->session()->get('filtres_mes_fiches');
                }else{
                    $types_selected = ['nom' => $default, 'id' => 0];
                    $request->session()->put('filtres_mes_fiches', $types_selected);
                }
            }

            
            $types = [
                ['nom' => $default, 'id' => 0],
                ['nom' => strtoupper(\Auth::user()->groupe->nom), 'id' => \Auth::user()->groupe->id],
            ];
        }else{
            $default = 'MES MARES';

            if($request->session()->get('filtres_mes_mares')){
                $types_selected = $request->session()->get('filtres_mes_mares');
            }else{
                $types_selected = ['nom' => $default, 'id' => 0];
                $request->session()->put('filtres_mes_mares', $types_selected);
            }
        }

        $formulaires = 
        Formulaire::
        with([
            'groupes' => function($query){
                $query->select('groupes.id', 'groupes.nom');
            }
        ])
        ->select('id', 'nom', 'nom_interne')
        ->get();

        return response()->json(compact('types', 'types_selected', 'statuts', 'formulaires', 'utilisateur'));
    }

    /*
     * Récupère toutes les mares du user
     * filtre par statut si indiqué
     */
    private function filterInput($request, $default)
    {
        $mare_id = $request->mare_id != null ? $request->mare_id : null;
        $statut_id = $request->statut_id != null ? $request->statut_id : null;
        $per_page = $request->per_page != null ? $request->per_page : 10;
        $current_sort = $request->currentSort != null ? $request->currentSort : $default;
        $current_sort_dir = $request->currentSortDir != null ? $request->currentSortDir : 'asc';
        return compact('statut_id', 'per_page', 'current_sort', 'current_sort_dir', 'mare_id');
    }



    /*
     * Affiche toutes les mares du user connecte
     */
    public function toutesMesMares(Request $request)
    {
        extract($this->filterInput($request, 'id'));

        $utilisateur = 
        Utilisateur::
        UtilisateurGroupeId()
        ->with([
            'role' => function($query){
                $query->select('id', 'nom_interne');
            }
        ])
        ->find(\Auth::id());

        $observateur_existe = Observateur::where('utilisateur_id', '=', \Auth::id())->first();

        $mares = Mare::MesMaresData()
                ->when(!$observateur_existe, function($query) use($observateur_existe){
                    $query->where('mares.utilisateur_id', '=', \Auth::id());
                })
                ->when($observateur_existe, function($query) use($observateur_existe){
                    $query->where(function($q) use($observateur_existe){
                        $q->where('mares.utilisateur_id', '=', \Auth::id())->orWhere('mares.observateur_id', '=', $observateur_existe->id);
                    });
                })
                ->when(!empty($statut_id), function($query) use($statut_id){
                    $query->where('mares.statut_id', '=', $statut_id);
                })
                ->when(!empty($mare_id), function($query) use($mare_id){
                    $query->where('mares.id', '=', $mare_id);
                })
                // ->distinct('mares.id')
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        $types_selected = ['nom' => 'MES MARES', 'id' => 0];
        $request->session()->put('filtres_mes_mares', $types_selected);

        $formulaires = 
        Formulaire::
        with([
            'groupes' => function($query){
                $query->select('groupes.id', 'groupes.nom');
            }
        ])
        ->select('id', 'nom', 'nom_interne')
        ->get();

        return response()->json(compact('mares', 'formulaires', 'utilisateur'));
    }


    /*
     * Récupère les mares du groupe du user
     * Filtre par statut si indiqué
     */
    public function maresDuGroupe(Request $request)
    {
        extract($this->filterInput($request, 'id'));
        extract($this->checkGroupeUser());
    
        $mares = Mare::MesMaresData()
                ->when(!empty($groupe), function($query) use($groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs){
                    $query->when(!empty($groupe_departements), function($q) use($groupe_departements){
                        $q->whereIn('mares.departement_code', $groupe_departements);
                    });
                    $query->when(!empty($groupe_intercommunalites), function($q) use($groupe_intercommunalites){
                        $q->whereIn('mares.intercommunalite_siren', $groupe_intercommunalites);
                    });
                    $query->when(!empty($groupe_communes), function($q) use($groupe_communes){
                        $q->whereIn('mares.commune_insee', $groupe_communes);
                    });
                    $query->when(!empty($groupe_utilisateurs), function($q) use($groupe_utilisateurs){
                        $q->whereIn('mares.utilisateur_id', $groupe_utilisateurs);
                    });
                    $query->when(empty($groupe_departements) && empty($groupe_intercommunalites) && empty($groupe_communes) && empty($groupe_utilisateurs), function($q){
                        $q->where('mares.utilisateur_id', '=', \Auth::id());
                    });
                })
                ->when(empty($groupe), function($query){
                    $query->where('mares.utilisateur_id', '=', \Auth::id());
                })
                ->when(!empty($statut_id), function($query) use($statut_id){
                    $query->where('statut_id', '=', $statut_id);
                })
                ->when(!empty($mare_id), function($query) use($mare_id){
                    $query->where('mares.id', '=', $mare_id);
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        $types_selected = ['nom' => strtoupper(\Auth::user()->groupe->nom), 'id' => \Auth::user()->groupe->id];
        $request->session()->put('filtres_mes_mares', $types_selected);

        return response()->json(compact('mares'));
    }
    

    /*
     * Affiche les fiches en attente en fonction du user
     */
    public function toutesMesFiches(Request $request)
    {
        extract($this->filterInput($request, 'id'));

        $observateur_existe = Observateur::where('utilisateur_id', '=', \Auth::id())->first();

        $fiches = Fiche::MesFichesData()
                ->whereHas('mare', function($query){
                    $query->whereHas('statut', function($q){
                        $q->where('nom_interne', '=', 'valider');
                    });
                })
                ->when(!$observateur_existe, function($query) use($observateur_existe){
                    $query->where('fiches.utilisateur_id', '=', \Auth::id());
                })
                ->when($observateur_existe, function($query) use($observateur_existe){
                    $query->where(function($q) use($observateur_existe){
                        $q->where('fiches.utilisateur_id', '=', \Auth::id())->orWhere('fiches.observateur_id', '=', $observateur_existe->id);
                    });
                })
                ->when(!empty($statut_id), function($query) use($statut_id){
                    $query->where('fiches.statut_id', '=', $statut_id);
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);


        $types_selected = ['nom' => 'MES FICHES', 'id' => 0];
        $request->session()->put('filtres_mes_fiches', $types_selected);

        return response()->json(compact('fiches'));
    }



    /*
     * Récupère les mares du groupe du user
     * Filtre par statut si indiqué
     */
    public function fichesDuGroupe(Request $request)
    {
        extract($this->filterInput($request, 'id'));
        extract($this->checkGroupeUser());

        $fiches = Fiche::MesFichesData()
                ->when(!empty($groupe), function($query) use($groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs){
                    $query->when(!empty($groupe_departements), function($q) use($groupe_departements){
                        $q->whereIn('mares.departement_code', $groupe_departements);
                    });
                    $query->when(!empty($groupe_intercommunalites), function($q) use($groupe_intercommunalites){
                        $q->whereIn('mares.intercommunalite_siren', $groupe_intercommunalites);
                    });
                    $query->when(!empty($groupe_communes), function($q) use($groupe_communes){
                        $q->whereIn('mares.commune_insee', $groupe_communes);
                    });
                    $query->when(!empty($groupe_utilisateurs), function($q) use($groupe_utilisateurs){
                        $q->whereIn('mares.utilisateur_id', $groupe_utilisateurs);
                    });
                    $query->when(empty($groupe_departements) && empty($groupe_intercommunalites) && empty($groupe_communes) && empty($groupe_utilisateurs), function($q){
                        $q->where('mares.utilisateur_id', '=', \Auth::id());
                    });
                })
                ->when(empty($groupe), function($query){
                    $query->where('mares.utilisateur_id', '=', \Auth::id());
                })
                ->when(!empty($statut_id), function($query) use($statut_id){
                    $query->where('fiches.statut_id', '=', $statut_id);
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        $types_selected = ['nom' => strtoupper(\Auth::user()->groupe->nom), 'id' => \Auth::user()->groupe->id];
        $request->session()->put('filtres_mes_fiches', $types_selected);

        return response()->json(compact('fiches'));
    }


    /*
     * Permet l'envoi d'un message dans mes mares ou mes fiches par rapport a une mare ou une fiche
     */
    public function sendCommentaire(Request $request)
    {
        if(Route::currentRouteName() === 'mes_mares.send_commentaires'){
            $validator = Validator::make($request->all(),[
                'mare_id' => 'required|numeric|exists:mares,id',
                'message' => 'required|string'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => true, 
                    'message' => $validator->messages()
                ]);
            }

            $mare = Mare::find($request->mare_id);

            if($mare){
               $commentaire = Commentaire::create([
                    'mare_id' => $request->mare_id,
                    'utilisateur_id' => \Auth::id(),
                    'statut_id' => $mare->statut_id,
                    'message' => $request->message,
                    'utilisateur_vu' => true,
                    'validateur_vu' => false,
                    'utilisateur_send' => true,
                ]); 

                $mare = Mare::MesMaresData()->find($commentaire->mare_id);
                $mare = $this->sanitizeMare($mare);

                return response()->json(compact('mare'));
            }

        }elseif(Route::currentRouteName() === 'mes_fiches.send_commentaires'){
            $validator = Validator::make($request->all(),[
                'fiche_id' => 'required|numeric|exists:fiches,id',
                'message' => 'required|string'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => true, 
                    'message' => $validator->messages()
                ]);
            }

            $fiche = Fiche::find($request->fiche_id);

            if($fiche){
                $mare = Mare::find($fiche->mare_id);
                $commentaire = Commentaire::create([
                    'fiche_id' => $request->fiche_id,
                    'utilisateur_id' => \Auth::id(),
                    'statut_id' => $mare ? $mare->statut_id : null,
                    'message' => $request->message,
                    'utilisateur_vu' => true,
                    'validateur_vu' => false,
                    'utilisateur_send' => true,
                ]); 

                $fiche = Fiche::MesFichesData()->find($commentaire->fiche_id);
                return response()->json(compact('fiche'));
            }

        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }


    /*
     * Passe le commentaire en Lu ou non en fonction du click du user
     */
    public function updateCommentaireLu(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'commentaire_id' => 'required|numeric|exists:commentaires_mares_fiches,id',
            'utilisateur_vu' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $commentaire = Commentaire::find($request->commentaire_id);

        if($commentaire){
            $commentaire->update(['utilisateur_vu' => $request->utilisateur_vu == 1 ? true : false]);

            if(Route::currentRouteName() === 'mes_mares.update_commentaire_lu'){
                $mare = Mare::MesMaresData()->find($commentaire->mare_id);
                $mare = $this->sanitizeMare($mare);
                return response()->json(compact('mare'));
            }elseif(Route::currentRouteName() === 'mes_fiches.update_commentaire_lu'){
                $fiche = Fiche::MesFichesData()->find($commentaire->fiche_id);
                return response()->json(compact('fiche'));
            }
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /*
     * Edition d'une mare + fiche si pas validé dans mes mares
     */
    public function edit($id)
    {
        $mare = Mare::MareWithAllRelation(true)->find($id);

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
            $utilisateur->groupe_departements = $groupe_departements;
            $utilisateur->groupe_intercommunalites = $groupe_intercommunalites;
            $utilisateur->groupe_communes = $groupe_communes;
            $utilisateur->groupe_utilisateurs = $groupe_utilisateurs;


            $mare = $this->sanitizeMare($mare);
            $fiche = $mare->fiches->first();

            $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('mare', 'fiche', 'utilisateur', 'observateur'));
            return response()->json($data);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }


    
    /*
     * Permet de mettre à jour une mare + fiche non validée
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

        if(!$request->fiche_id){
            return response()->json([
                'error' => true, 
                'message' => [['Aucune fiche n\'a été trouvée']]
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


            $valide = false;
            $fiche = Fiche::find($request->fiche_id);

            if($fiche){
                $fiche->update([
                    'utilisateur_id' => \Auth::id(),
                    'mare_id' => $mare->id,
                    'valide' => $valide,
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
                    'observateur_id' => $request->observateur_id
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


                if($request->hasFile('photo') && !$fiche->photos()->exists()){
                    $path = $this->createPhoto($request, $mare, $fiche);

                    if(!$path){
                        return response()->json([
                            'error' => false, 
                            'message' => [['La mare a été enregistrée mais un problème est survenu lors de l\'enregistrement de la photo. La mare sera visible une fois validée']]
                        ]);
                    }
                }

                $error = false;
                $message = [['La mare a bien été modifiée. Elle sera visible une fois validée.']];
                return response()->json(compact('error', 'message'));
            }
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }



    /*
     * Permet l'édition d'une fiche
     */
    public function editFicheAttente($id)
    {
        $mare = Mare::MareWithAllRelation(false, false, $id)
            ->join('fiches as f', 'f.mare_id', '=', 'mares.id')
            ->where('f.id', '=', $id)
            ->select('mares.*')
            ->first();

        if($mare){
            $utilisateur = Utilisateur::UtilisateurGroupeId()->find(\Auth::id());
            $observateur = Observateur::where('utilisateur_id', '=', \Auth::id())->first();
            
            extract($this->checkGroupeUser());
            $utilisateur->groupe_departements = $groupe_departements;
            $utilisateur->groupe_intercommunalites = $groupe_intercommunalites;
            $utilisateur->groupe_communes = $groupe_communes;
            $utilisateur->groupe_utilisateurs = $groupe_utilisateurs;

            $mare = $this->sanitizeMare($mare);
            $fiche = $mare->fiches->first();

            $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('utilisateur', 'mare', 'fiche', 'observateur'));
            return response()->json($data);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }




    /*
     * Permet de mettre à jour une fiche non validée
     */
    public function updateFicheAttente(Request $request, $id){
        $validate = $this->validateFiche($request);
        if($validate['error'] == true){
            return response()->json([
                'error' => $validate['error'], 
                'message' => $validate['message']
            ]);
        }

        $fiche = Fiche::find($id);

        if($fiche){
            $mare = Mare::find($fiche->mare_id);

            $mare->update([
                'nom' => $request->nom_mare,
                'type_observateur_id' => $request->type_observateur_id,
                'type_propriete_id' => $request->type_propriete_id, 
                'situation_topographie_id' => $request->situation_topographie_id,
                'code_ofb' => $request->code_ofb
            ]);

            $valide = false;

            $fiche->update([
                'utilisateur_id' => \Auth::id(),
                'mare_id' => $mare->id,
                'valide' => $valide,
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


            if($request->hasFile('photo') && !$fiche->photos()->exists()){
                $path = $this->createPhoto($request, $mare, $fiche);

                if(!$path){
                    return response()->json([
                        'error' => false, 
                        'message' => [['La mare a été enregistrée mais un problème est survenu lors de l\'enregistrement de la photo. La mare sera visible une fois validée']]
                    ]);
                }
            }



            $error = false;
            $message = [['La mare a bien été modifiée. Elle sera visible une fois validée.']];
            return response()->json(compact('error', 'message'));

        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }



    /**
     * Permet la suppression d'une mare
     */
    public function destroy($id)
    {
        $mare = Mare::find($id);

        if($mare && \Auth::user()->hasRole('administrateur') || \Auth::user()->hasRole('gestionnaire') || \Auth::user()->hasRole('developpeur')){
            if($mare->fiches()->exists()){
                foreach ($mare->fiches as $fiche) {
                    if($fiche->usage_mares()->exists()){
                        $fiche->usage_mares()->sync([]);
                    }
                    if($fiche->usage_dechets()->exists()){
                        $fiche->usage_dechets()->sync([]);
                    }
                    if($fiche->situation_contextes()->exists()){
                        $fiche->situation_contextes()->sync([]);
                    }
                    if($fiche->situation_batis()->exists()){
                        $fiche->situation_batis()->sync([]);
                    }
                    if($fiche->hydrologie_reseaus()->exists()){
                        $fiche->hydrologie_reseaus()->sync([]);
                    }
                    if($fiche->hydrologie_alimentations()->exists()){
                        $fiche->hydrologie_alimentations()->sync([]);
                    }
                    if($fiche->interventions()->exists()){
                        $fiche->interventions()->sync([]);
                    }
                    if($fiche->faunes()->exists()){
                        $fiche->faunes()->sync([]);
                    }
                    if($fiche->flores()->exists()){
                        $fiche->flores()->sync([]);
                    }
                    if($fiche->eee_faunes()->exists()){
                        $fiche->eee_faunes()->sync([]);
                    }
                    if($fiche->eee_flores()->exists()){
                        $fiche->eee_flores()->sync([]);
                    }

                    Commentaire::where('fiche_id', '=', $fiche->id)->delete();

                    if($fiche->photos()->exists()){
                        foreach ($fiche->photos as $photo) {
                            $file = $photo->nom;
                            $path = $photo->chemin;
                            $this->removePhoto($file, $path, $fiche->id, $mare->id);
                            $photo->delete();
                        }
                    }

                    $fiche->delete();
                }
            }

            Commentaire::where('mare_id', '=', $mare->id)->delete();
            $mare->delete();

            return response()->json([
                'error' => false, 
                'message' => [['La mare et ses fiches ont bien été supprimés']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);

    }


    public function destroyFiche($id)
    {
        $fiche = Fiche::find($id);

        if($fiche && \Auth::user()->hasRole('administrateur') || \Auth::user()->hasRole('gestionnaire') || \Auth::user()->hasRole('developpeur')){
            if($fiche->usage_mares()->exists()){
                $fiche->usage_mares()->sync([]);
            }
            if($fiche->usage_dechets()->exists()){
                $fiche->usage_dechets()->sync([]);
            }
            if($fiche->situation_contextes()->exists()){
                $fiche->situation_contextes()->sync([]);
            }
            if($fiche->situation_batis()->exists()){
                $fiche->situation_batis()->sync([]);
            }
            if($fiche->hydrologie_reseaus()->exists()){
                $fiche->hydrologie_reseaus()->sync([]);
            }
            if($fiche->hydrologie_alimentations()->exists()){
                $fiche->hydrologie_alimentations()->sync([]);
            }
            if($fiche->interventions()->exists()){
                $fiche->interventions()->sync([]);
            }
            if($fiche->faunes()->exists()){
                $fiche->faunes()->sync([]);
            }
            if($fiche->flores()->exists()){
                $fiche->flores()->sync([]);
            }
            if($fiche->eee_faunes()->exists()){
                $fiche->eee_faunes()->sync([]);
            }
            if($fiche->eee_flores()->exists()){
                $fiche->eee_flores()->sync([]);
            }

            Commentaire::where('fiche_id', '=', $fiche->id)->delete();

            $mare = Mare::find($fiche->mare_id);

            if($mare && $fiche->photos()->exists()){
                foreach ($fiche->photos as $photo) {
                    $file = $photo->nom;
                    $path = $photo->chemin;
                    $this->removePhoto($file, $path, $fiche->id, $mare->id);
                    $photo->delete();
                }
            }

            $fiche->delete();

            return response()->json([
                'error' => false, 
                'message' => [['La fiche a bien été supprimé']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }


    public function saveCodeOfb(Request $request)
    {
        if(!empty($request->code_ofb) && !empty($request->mare_id)){
            $mare = Mare::find($request->mare_id);
            if(!empty($mare)){
                $mare->update(['code_ofb' => $request->code_ofb]);
                return response()->json($this->success("L'organisme", "créé"));
            }
        }

        return response()->json($this->error);
    }

}
