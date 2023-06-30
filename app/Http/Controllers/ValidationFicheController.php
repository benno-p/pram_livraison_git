<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\MareTrait;
use App\Mare;
use App\Fiche;
use App\Utilisateur;
use App\Intercommunalite;
use App\Commune;
use App\Statut;
use App\Caracterisation;
use App\Formulaire;
use App\Commentaire;
use Carbon\Carbon;
use Validator;
use App\Mail\NotificationValidationMareFicheUtilisateur;
use Illuminate\Support\Facades\Mail;

class ValidationFicheController extends Controller
{
    use MareTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Utilisateur::find(\Auth::id());

        $departements_user = [];

        if($user->departements){
            foreach ($user->departements as $dep) {
                $departements_user[] = $dep->code_insee;
            }
        }


        $fiches = Fiche::MesFichesData()
                    ->whereHas('mare', function($query) use($user, $departements_user){
                        $query
                        ->whereHas('statut', function($q){
                            $q->where('nom_interne', '=', 'valider');
                        })
                        ->when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($q) use($departements_user){
                            $q->whereIn('departement_code', $departements_user);
                        });
                    })
                    ->whereHas('statut', function($query){
                        $query->where('nom_interne', '=', 'en_attente_de_validation');
                    })
                    ->orWhereDoesntHave('statut')
                    ->get();

        $statuts = Statut::all();
        
        return response()->json(compact('fiches', 'statuts'));
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
        $validator = Validator::make($request->all(),[
            // 'valide' => 'required|integer|between:0,1',
            'commentaire' => 'nullable|string|max:255',
            // 'mare_id' => 'required|exists:mares,id',
            'fiche_id' => 'required|numeric|exists:fiches,id',
            'validation_type' => 'required|string',
            'caracterisation_id' => 'nullable|numeric|exists:caracterisations,id',
            'statut_id' => 'required|numeric|exists:statuts,id'
        ]);

        if($validator->fails()) {
            $response = [
                'error' => true,
                'message' => $validator->messages(),
            ];
        }

        $fiche = Fiche::find($request->fiche_id);
        $statut = Statut::find($request->statut_id);

        if($request->validation_type == 'fiche' && $fiche && $statut){

            $commentaire = '';
            $caracterisation = Caracterisation::find($request->caracterisation_id);
            $caracterisation_label = $caracterisation ? $caracterisation->nom : 'N/C';
            $utilisateur_vu = false;

            if($statut->nom_interne === 'en_attente_de_validation' || $statut->nom_interne === 'refuser'){
                $commentaire = $request->commentaire;
            }elseif($statut->nom_interne === 'valider'){
                $commentaire = 'Fiche validée le : '.Carbon::now()->format('d/m/Y').' par '.\Auth::user()->prenom.' '.\Auth::user()->nom. ' - Caractérisation de la mare : '.$caracterisation_label;
                $utilisateur_vu = true;
            }

            Commentaire::create([
                'fiche_id' => $fiche->id,
                'utilisateur_id' => \Auth::id(),
                'statut_id' => $request->statut_id,
                'message' => $commentaire,
                'utilisateur_vu' => $utilisateur_vu,
                'validateur_vu' => true,
                'validateur_send' => true
            ]);

            $fiche->update([
                // 'valide' => $request->valide == 1 ? true : false,
                'statut_id' => $request->statut_id,
                'commentaire_validation' => $request->commentaire,
                'caracterisation_id' => $request->caracterisation_id,
            ]);

            $mare = Mare::find($fiche->mare_id);

            if($mare){
                $mare->update([
                    'caracterisation_id' => $request->caracterisation_id,
                ]);
            }

            // if($fiche->utilisateur_id && $fiche->utilisateur->email && $mare){
            //     $validation = $request->valide;
            //     $type = 'fiche';
            //     Mail::to($fiche->utilisateur->email)->send(new NotificationValidationMareFicheUtilisateur($mare, $fiche, $validation, $type));
            // }


            $error = false;
            $message = [['La mare a été mise à jour']];
            return response()->json(compact('error', 'message'));
        }


        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $mare = Mare::MareWithAllRelation(false, false, $id)
            ->join('fiches as f', 'f.mare_id', '=', 'mares.id')
            ->where('f.id', '=', $id)
            ->select('mares.*')
            ->first();

        if($mare){
            $utilisateur = 
            Utilisateur::
            UtilisateurGroupeId()
            ->with([
                'role' => function($query){
                    $query->select('id', 'nom_interne');
                }
            ])
            ->find(\Auth::id());

            $mare_intercommunalite = Intercommunalite::where('siren', '=', $mare->intercommunalite_siren)->first();

            if($mare_intercommunalite){
                $mare->intercommunalite = $mare_intercommunalite->raison_soc;
            }

            $mare_commune = Commune::where('insee', '=', $mare->commune_insee)->first();
            if($mare_commune){
                $mare->commune = $mare_commune->nom;
            }


            $data = (object) array_merge((array) $this->getAllSelects(), (array) compact('utilisateur', 'mare'));
            return response()->json($data);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fiche = Fiche::with('statut')->find($id);

        if($fiche){
            $mare = Mare::find($fiche->mare_id);
            if($mare){
                $statuts = Statut::all();
                $caracterisations = Caracterisation::
                    orderby('nom', 'asc')
                    ->get();

                return response()->json(compact('caracterisations', 'mare', 'statuts', 'fiche'));
            }
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
