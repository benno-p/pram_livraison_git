<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\NouvelUtilisateur;
use Illuminate\Support\Facades\Mail;
use App\Utilisateur;
use App\Departement;
use App\Role;
use App\Groupe;
use App\Observateur;
use Validator;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilisateurs = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
                        ->when(!\Auth::user()->hasRole('developpeur'), function($query){
                            $query->where('r.nom_interne', '!=', 'developpeur');
                        })
                        ->orderby('utilisateurs.nom', 'asc')
                        ->select('utilisateurs.*')
                        ->get();

        $groupes = Groupe::orderby('nom', 'asc')->get();

        foreach ($utilisateurs as $utilisateur) {
            $role = $utilisateur->role->nom;
            unset($utilisateur->role);
            $utilisateur->role = $role;

            $groupe = $utilisateur->groupe && $utilisateur->groupe->nom ? $utilisateur->groupe->nom : '';
            unset($utilisateur->groupe);
            $utilisateur->groupe = $groupe; 
        }

        return response()->json(compact('utilisateurs', 'groupes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderby('nom', 'asc')
                ->when(!\Auth::user()->hasRole('developpeur'), function($query){
                    $query->where('nom_interne', '!=', 'developpeur');
                })
                ->get();

        $departements = Departement::orderby('nom', 'asc')->get();
        $groupes = Groupe::orderby('nom', 'asc')->get();

        return response()->json(compact('roles', 'departements', 'groupes'));
    }


    /*
     * Check si l'utilisateur en création à déjà un homonyme dans la DB pour l'associer si c'est le cas
     */
    public function  checkIfObservateurExist(Request $request){
        $nom = strtolower($request->nom);
        $prenom = strtolower(str_slug($request->prenom));

        $observateur = Observateur::whereRaw('lower(nom) like (?)', ["{$nom}"])
                ->whereRaw('lower(prenom) like (?)', ["{$prenom}"])
                ->first();

        if($observateur){
            return response()->json([
                'match' => true,
                'observateur' => $observateur,
                'message' => 'Un observateur portant le même nom et prénom à été trouvé dans la base de données. Voulez-vous associer cet observateur à cet utilisateur ?'
            ]);
        }

        return response()->json([
            'match' => false,
        ]);
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
        $utilisateur = Utilisateur::with('role', 'departements')->find($id);

        if($utilisateur){
            $roles = role::orderby('nom', 'asc')
                    ->when(!\Auth::user()->hasRole('developpeur'), function($query){
                        $query->where('nom_interne', '!=', 'developpeur');
                    })
                    ->get();

            $departements = Departement::orderby('nom', 'asc')->get();
            $groupes = Groupe::orderby('nom', 'asc')->get();

            return response()->json(compact('utilisateur', 'roles', 'departements', 'groupes')); 
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
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'email' => 'required|email|max:250',
            'role_id' => 'required|numeric|exists:roles,id',
            'actif' => 'required|numeric',
            'departements' => 'nullable|array|min:1',
            'departements.*' => 'nullable|numeric|exists:departements,id',
            'groupe_id' => 'nullable|numeric|exists:groupes,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }


        $utilisateur = Utilisateur::find($id);

        if($utilisateur){
            $utilisateur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'actif' => $request->actif == '1' ? 1 : 0,
                'groupe_id' => $request->groupe_id
            ]);

            $observateur = Observateur::updateOrCreate(
                ['utilisateur_id' => $id],
                ['nom' => $request->nom, 'prenom' => $request->prenom, 'utilisateur_id' => $id]
            );

            if($request->departements){
                $utilisateur->departements()->sync($request->departements);
            }else{
                $utilisateur->departements()->sync([]);
            }

            $error = false;
            $message = [['L\'utilisateur a bien été modifié']]; 
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
        $utilisateur = Utilisateur::find($id);

        if($utilisateur){
            if($utilisateur->actif == 1){
                $utilisateur->update(['actif' => 0]);
                $message = 'L\'utilisateur a été désactivé';
            }else{
                $utilisateur->update(['actif' => 1]);
                $message = 'L\'utilisateur a été activé';
            }

            $user = Utilisateur::find($id);
            $role = $user->role->nom;
            unset($user->role);
            $user->role = $role;

            return response()->json([
                'error' => false, 
                'message' => [[$message]],
                'utilisateur' => $user,
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']],
        ]);
    }



    /*
     * Permet au user d'update son MDP
     */
    public function updatePassword(Request $request)
    {
        $now_password = $request->now_password;

        if (Hash::check($now_password, Auth::user()->password)) {
            $validator = Validator::make($request->all(),[
                'now_password' => 'required|min:8',
                'password' => 'required|min:8|confirmed|different:now_password',
                'password_confirmation' => 'required|min:8',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => true, 
                    'message' => $validator->messages()
                ]);
            }
            
            $id = Auth::id();
            $utilisateur = Utilisateur::find($id);
            $test = $utilisateur->update(['password' => bcrypt($request->password)]);
            
            if($test){
                return response()->json([
                    'error' => false, 
                    'message' => [['Votre mot de passe a été mis à jour']]
                ]);
            }else{
                return response()->json([
                    'error' => true, 
                    'message' => [['Une erreur est survenue']]
                ]);
            }
        }

        return response()->json([
            'error' => true, 
            'message' => [['Le mot de passe actuel ne correspond pas']]
        ]);
    }
}
