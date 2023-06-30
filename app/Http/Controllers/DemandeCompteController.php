<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DemandeCompte;
use App\Role;
use App\Departement;
use App\Utilisateur;
use App\Groupe;
use App\Observateur;
use App\Mail\NouvelUtilisateur;
use App\Mail\DemandeCreationCompte;
use Illuminate\Support\Facades\Mail;
use Validator;

class DemandeCompteController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:access_validation_compte,App\Role', ['except' => ['create','store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demande_comptes = DemandeCompte::where('valide', false)->orderby('created_at', 'desc')->get();
        return response()->json(compact('demande_comptes'));
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
            'nom' => "required|string|max:150",
            'prenom' => "required|string|max:150",
            'email' => "required|string|max:150|unique:utilisateurs,email|unique:demande_comptes,email",
            'telephone_fixe' => "nullable|string|max:20",
            'telephone_portable' => "nullable|string|max:20",
            'organisme' => 'required|string|max:255',
            'motivation' => 'required|string|max:255'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $demande = DemandeCompte::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone_fixe' => $request->telephone_fixe,
            'telephone_portable' => $request->telephone_portable,
            'organisme' => $request->organisme,
            'motivation' => $request->motivation,
            'valide' => false,
        ]);


        if($demande){
            $error = false;

            $emails = $this->checkEmailsAdministrateurs();
            if(!empty($emails)){
                Mail::to($emails)->send(new DemandeCreationCompte($demande));
            }

            $message = [['Votre demande a bien été envoyée. Votre compte sera actif après validation.']];
            return response()->json(compact('error', 'message'));
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }


    /*
     * Renvoi les mails du/des admins
     */
    private function checkEmailsAdministrateurs()
    {
        $emails = [];
        $emails = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
                ->where('r.nom_interne', '=', 'administrateur')
                ->pluck('utilisateurs.email')
                ->toArray();

        return $emails;
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
        $demande_compte = DemandeCompte::find($id);

        if($demande_compte){
            $roles = role::orderby('nom', 'asc')
                    ->when(!\Auth::user()->hasRole('developpeur'), function($query){
                        $query->where('nom_interne', '!=', 'developpeur');
                    })
                    ->get();

            $departements = Departement::orderby('nom', 'asc')->get();
            $groupes = Groupe::orderby('nom', 'asc')->get();

            return response()->json(compact('demande_compte', 'roles', 'departements', 'groupes'));
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
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
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'email' => 'required|email|max:250|unique:utilisateurs,email',
            'role_id' => 'required|numeric|exists:roles,id',
            'departements' => 'nullable|array|min:1',
            'departements.*' => 'nullable|numeric|exists:departements,id',
            'groupe_id' => 'nullable|numeric|exists:groupes,id',
            'observateur_id' => 'nullable|numeric|exists:observateurs,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $demande_compte = DemandeCompte::find($id);

        if($demande_compte){
            $demande_compte->update(['valide' => true]);
        }

        $password = str_random(8);

        $utilisateur = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'actif' => 1,
            'password' => bcrypt($password),
            'groupe_id' => $request->groupe_id
        ]);

        /*
         * Si observateur_id != null, on update l'observateur associé a l'id du user
         */
        if($request->observateur_id != null){
            $observateur = Observateur::find($request->observateur_id);

            if($observateur){
                $observateur->update(['utilisateur_id' => $utilisateur->id]);
            }else{
                $observateur = Observateur::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'utilisateur_id' => $utilisateur->id
                ]);
            }
        }else{
            $observateur = Observateur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'utilisateur_id' => $utilisateur->id
            ]);
        }

        if($request->departements){
            $utilisateur->departements()->sync($request->departements);
        }

        if($utilisateur){
            Mail::to($request->email)->send(new NouvelUtilisateur($utilisateur, $password));

            $error = false;
            $message = [['L\'utilisateur a bien été enregistré']];

            return response()->json(compact('error', 'message'), 200);
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
}
