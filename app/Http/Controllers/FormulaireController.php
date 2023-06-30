<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Formulaire;
use App\Groupe;
use Validator;

class FormulaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $formulaires =  Formulaire::orderby('nom', 'asc')->get();

        foreach ($formulaires as $form) {
            $form->groupe = 'Aucun';
            if($form->groupes->count() > 0){
                $string = '';
                foreach ($form->groupes as $groupe) {
                    $string .= $groupe->nom. ' - ';
                }
                $form->groupe = substr($string, 0, -3);
            }
        }

        return response()->json(compact('formulaires'));
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formulaire = Formulaire::with('groupes')->find($id);

        if($formulaire){
            $groupes = Groupe::orderby('nom', 'asc')->get();
            return response()->json(compact('formulaire', 'groupes'));
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
            'groupes' => 'nullable|array',
            'groupes.*' => 'numeric|exists:groupes,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $formulaire = Formulaire::find($id);

        if($formulaire){
            if($request->groupes != null){
                $formulaire->groupes()->sync($request->groupes);
            }else{
                $formulaire->groupes()->sync([]);
            }

            return response()->json([
                'error' => false, 
                'message' => [['Le formulaire a bien été modifié']]
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
        //
    }
}
