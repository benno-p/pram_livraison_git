<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Observateur;
use Validator;

class ObservateurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observateurs = Observateur::orderby('nom', 'asc')->get();
        return response()->json(compact('observateurs'));
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


    private function checkIfHomonyme($request)
    {
        $nom = strtolower($request->nom);
        $prenom = strtolower($request->prenom);

        $test = Observateur::whereRaw('lower(nom) like (?)', ["{$nom}"])
                ->whereRaw('lower(prenom) like (?)', ["{$prenom}"])
                ->first();

        return $test;
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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'organisme' => 'nullable|string|max:255',
            'nom_source' => 'nullable|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        if($this->checkIfHomonyme($request)){
            return response()->json([
                'error' => true, 
                'message' => [['Une personne avec le même nom et prénom existe déjà dans la base de donnée']]
            ]);
        }

        $observateur = Observateur::create($request->all());

        if($observateur){

            $observateur = Observateur::select('*')->selectRaw("CONCAT(nom,' ',prenom) as label")->find($observateur->id);

            return response()->json([
                'error' => false,
                'observateur' => $observateur,
                'message' => [['L\'observateur a bien été enregistré']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
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
        $observateur = Observateur::find($id);

        if($observateur){
            return response()->json(compact('observateur'));
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
        $validator = Validator::make($request->all(),[
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'organisme' => 'nullable|string|max:255',
            'nom_source' => 'nullable|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $observateur = Observateur::find($id);

        if($observateur){
            $observateur->update($request->all());

            return response()->json([
                'error' => false, 
                'message' => [['L\'observateur a bien été modifié']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $observateur = Observateur::find($id);

        if($observateur){
            if($observateur->mares()->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Vous ne pouvez pas supprimer cet observateur car une relation avec une ou plusieurs mares a été trouvée']]
                ]);
            }
            if($observateur->fiches()->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Vous ne pouvez pas supprimer cet observateur car une relation avec une ou plusieurs fiches a été trouvée']]
                ]);
            }

            if($observateur->utilisateur()->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Vous ne pouvez pas supprimer cet observateur car il est relié à un utilisateur']]
                ]);
            }

            $observateur->delete();

            return response()->json([
                'error' => false, 
                'message' => [['L\'observateur a bien été supprimé']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }
}
