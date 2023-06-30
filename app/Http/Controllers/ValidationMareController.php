<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mare;
use App\Fiche;
use App\Utilisateur;
use App\Caracterisation;
use App\Traits\MareTrait;
use Validator;
use App\Mail\NotificationValidationMareFicheUtilisateur;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Statut;
use App\Commentaire;

class ValidationMareController extends Controller
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

        $mares = Mare::MesMaresData()
                    ->when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($query) use($departements_user){
                        $query->whereIn('departement_code', $departements_user);
                    })
                    // ->where('valide', '=', false)
                    ->whereHas('statut', function($query){
                        $query->where('nom_interne', '=', 'en_attente_de_validation');
                    })
                    ->get();

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);  
        }

        $statuts = Statut::all();

        return response()->json(compact('mares', 'statuts'));
    }


    public function sendCommentaire(Request $request)
    {
        if(Route::currentRouteName() === 'validation_mares.send_commentaires'){
            $validator = Validator::make($request->all(),[
                'mare_id' => 'required|numeric|exists:mares,id',
                'message' => 'required|string',
                'statut_id' => 'required|numeric|exists:statuts,id'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => true, 
                    'message' => $validator->messages()
                ]);
            }

            $mare = Mare::find($request->mare_id);

            if($mare){
                $mare->update(['statut_id' => $request->statut_id]);

                $commentaire = Commentaire::create([
                    'mare_id' => $request->mare_id,
                    'utilisateur_id' => \Auth::id(),
                    'statut_id' => $mare->statut_id,
                    'message' => $request->message,
                    'utilisateur_vu' => false,
                    'validateur_vu' => true,
                    'validateur_send' => true,
                ]); 

                $mare = Mare::MesMaresData()->find($commentaire->mare_id);
                $mare = $this->sanitizeMare($mare);

                return response()->json(compact('mare'));
            }
        }elseif(Route::currentRouteName() === 'validation_fiches.send_commentaires'){
            $validator = Validator::make($request->all(),[
                'fiche_id' => 'required|numeric|exists:fiches,id',
                'message' => 'required|string',
                'statut_id' => 'required|numeric|exists:statuts,id'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => true, 
                    'message' => $validator->messages()
                ]);
            }

            $fiche = Fiche::find($request->fiche_id);

            if($fiche){
                $fiche->update(['statut_id' => $request->statut_id]);
                $commentaire = Commentaire::create([
                    'fiche_id' => $request->fiche_id,
                    'utilisateur_id' => \Auth::id(),
                    'statut_id' => $fiche->statut_id,
                    'message' => $request->message,
                    'utilisateur_vu' => false,
                    'validateur_vu' => true,
                    'validateur_send' => true,
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


    public function updateCommentaireLu(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'commentaire_id' => 'required|numeric|exists:commentaires_mares_fiches,id',
            'validateur_vu' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $commentaire = Commentaire::find($request->commentaire_id);

        if($commentaire){
            $commentaire->update(['validateur_vu' => $request->validateur_vu == 1 ? true : false]);

            if(Route::currentRouteName() === 'validation_mares.update_commentaire_lu'){
                $mare = Mare::MesMaresData()->find($commentaire->mare_id);
                $mare = $this->sanitizeMare($mare);
                return response()->json(compact('mare'));
            }elseif(Route::currentRouteName() === 'validation_fiches.update_commentaire_lu'){
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return var_dump($request->caracterisation_id);
        $validator = Validator::make($request->all(),[
            // 'valide' => 'required|integer|between:0,1',
            'commentaire' => 'nullable|string|max:255',
            'mare_id' => 'required|exists:mares,id',
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

        $mare = Mare::find($request->mare_id);
        $statut = Statut::find($request->statut_id);

        if($request->validation_type == 'mare' && $mare && $statut){

            $commentaire = '';
            $caracterisation = Caracterisation::find($request->caracterisation_id);
            $caracterisation_label = $caracterisation ? $caracterisation->nom : 'N/C';
            $utilisateur_vu = false;

            if($statut->nom_interne === 'en_attente_de_validation' || $statut->nom_interne === 'refuser'){
                $commentaire = $request->commentaire;
            }elseif($statut->nom_interne === 'valider'){
                $commentaire = 'Mare validée le : '.Carbon::now()->format('d/m/Y').' par '.\Auth::user()->prenom.' '.\Auth::user()->nom. ' - Caractérisation de la mare : '.$caracterisation_label;
                $utilisateur_vu = true;
            }

            Commentaire::create([
                'mare_id' => $mare->id,
                'utilisateur_id' => \Auth::id(),
                'statut_id' => $request->statut_id,
                'message' => $commentaire,
                'utilisateur_vu' => $utilisateur_vu,
                'validateur_vu' => true,
                'validateur_send' => true
            ]);


            $mare->update([
                // 'valide' => $request->valide == 1 ? true : false,
                'statut_id' => $request->statut_id,
                'commentaire_validation' => $request->commentaire,
                'caracterisation_id' => $request->caracterisation_id,
            ]);

            if($mare->commentaires){
                foreach($mare->commentaires as $commentaire){
                    $commentaire->update(['validateur_vu' => true]);
                }
            }

            if($mare->fiches()->exists()){
                foreach ($mare->fiches as $fiche){
                    $fiche->update([
                        // 'valide' => $request->valide == 1 ? true : false,
                        'statut_id' => $request->statut_id,
                        'caracterisation_id' => $request->caracterisation_id,
                    ]);
                }
            }


            // if($mare->utilisateur_id && $mare->utilisateur->email){
            //     $validation = $request->valide;
            //     $type = 'mare';
            //     Mail::to($mare->utilisateur->email)->send(new NotificationValidationMareFicheUtilisateur($mare, $fiche, $validation, $type));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $caracterisations = Caracterisation::
                            where('nom', '!=', 'En attente de validation')
                            ->orderby('nom', 'asc')
                            ->get();

        $statuts = Statut::all();
        $mare = Mare::with('statut')
                ->select('id', 'statut_id')
                ->find($id);

        return response()->json(compact('caracterisations', 'statuts', 'mare'));
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
