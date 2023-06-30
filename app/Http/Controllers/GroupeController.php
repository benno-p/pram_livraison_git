<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Groupe;
use App\Departement;
use App\Intercommunalite;
use App\Commune;
use Validator;

class GroupeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupes = Groupe::orderby('nom', 'asc')->get();
        return response()->json(compact('groupes'));
    }

    private function getDepIntercoCommuneTypeForCreateEdit()
    {
        $departements = Departement::select('id', 'code_insee', 'nom')
                        ->orderby('nom', 'asc')
                        ->get();

        $intercommunalites = Intercommunalite::select('id', 'siren', 'raison_soc', 'dept')
                            ->orderby('raison_soc', 'asc')
                            ->get();

        $communes = Commune::select('id', 'insee', 'nom')
                    ->selectRaw("CONCAT(nom,' ',insee) as label")
                    ->orderby('nom', 'asc')
                    ->get();

        $types = [
            ['nom' => 'Départements', 'nom_interne' => 'departements'],
            ['nom' => 'Intercommunalités', 'nom_interne' => 'intercommunalites'],
            ['nom' => 'Communes', 'nom_interne' => 'communes'],
            ['nom' => 'Groupe', 'nom_interne' => 'groupe']
        ];

        return compact('departements', 'intercommunalites', 'communes', 'types');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        extract($this->getDepIntercoCommuneTypeForCreateEdit());
        return response()->json(compact('departements', 'intercommunalites', 'communes', 'types'));
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
            'nom' => 'required|string|unique:groupes,nom',
            'departements' => 'nullable|array|min:1',
            'departements.*' => 'numeric|exists:departements,id',
            'intercommunalites' => 'nullable|array',
            'intercommunalites.*' => 'numeric|exists:intercommunalites,id',
            'communes' => 'nullable|array',
            'communes.*' => 'numeric|exists:communes,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $groupe = Groupe::create($request->all());

        if($groupe){
            if($request->departements){
                $groupe->departements()->sync($request->departements);
            }

            if($request->intercommunalites){
                $groupe->intercommunalites()->sync($request->intercommunalites);
            }

            if($request->communes){
                $groupe->communes()->sync($request->communes);
            }

            return response()->json([
                'error' => false, 
                'message' => [['Le groupe a bien été enregistré']]
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
        $groupe = Groupe::with([
                    'departements' => function($query){
                        $query->select('departements.id', 'code_insee', 'nom');
                    },
                    'intercommunalites' => function($query){
                        $query->select('intercommunalites.id', 'siren', 'raison_soc', 'dept');
                    },
                    'communes' => function($query){
                        $query->select('communes.id', 'insee', 'nom')
                            ->selectRaw("CONCAT(nom,' ',insee) as label");
                    }
                ])->find($id);

        if($groupe){
            extract($this->getDepIntercoCommuneTypeForCreateEdit());
            return response()->json(compact('groupe', 'departements', 'intercommunalites', 'communes', 'types'));
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
            'nom' => 'required|string',
            'departements' => 'nullable|array|min:1',
            'departements.*' => 'numeric|exists:departements,id',
            'intercommunalites' => 'nullable|array',
            'intercommunalites.*' => 'numeric|exists:intercommunalites,id',
            'communes' => 'nullable|array',
            'communes.*' => 'numeric|exists:communes,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $groupe = Groupe::find($id);

        if($groupe){

            $groupe->update($request->all());

            if($request->departements != null){
                $groupe->departements()->sync($request->departements);
            }else{
                $groupe->departements()->sync([]);
            }

            if($request->intercommunalites != null){
                $groupe->intercommunalites()->sync($request->intercommunalites);
            }else{
                $groupe->intercommunalites()->sync([]);
            }

            if($request->communes != null){
                $groupe->communes()->sync($request->communes);
            }else{
                $groupe->communes()->sync([]);
            }

            return response()->json([
                'error' => false, 
                'message' => [['Le groupe a bien été modifié']]
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
        $groupe = Groupe::find($id);

        if($groupe){
            if($groupe->utilisateurs()->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Vous ne pouvez pas supprimer ce groupe car une relation avec un ou plusieurs utilisateurs a été trouvée']]
                ]);
            }else{
                if($groupe->departements()->exists()){
                    $groupe->departements()->sync([]);
                }
                if($groupe->intercommunalites()->exists()){
                    $groupe->intercommunalites()->sync([]);
                }
                if($groupe->communes()->exists()){
                    $groupe->communes()->sync([]);
                }
                $groupe->delete();

                return response()->json([
                    'error' => false, 
                    'message' => [['Le groupe a bien été supprimé']]
                ]);
            }
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);  

    }
}
