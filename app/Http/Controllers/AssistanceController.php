<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Ticket;
use App\Utilisateur;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Mail;

class AssistanceController extends Controller
{
	public function createTicket(Request $request)
	{
		$validator = Validator::make($request->all(),[
            'objet' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }


        $user = Utilisateur::find(\Auth::id());

        if(!$user){
            return response()->json([
                'error' => true, 
                'message' => [["Une erreur est survenue. Vous ne semblez pas être connecté"]]
            ]);
        }


        $ticket = Ticket::create([
        	'utilisateur_nom' => $user->nom,
        	'utilisateur_prenom' => $user->prenom,
        	'utilisateur_email' => $user->email,
        	'application' => 'pram',
        	'type' => $request->type,
        	'objet' => $request->objet,
        	'description' => $request->description,
        	'traite' => false,
        ]);


        if($ticket){
        	$mails = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
        			->where('r.nom_interne', '=', 'developpeur')
        			->orwhere('r.nom_interne', '=', 'administrateur')
        			->pluck('utilisateurs.email')
        			->toArray();

        	if($mails){
        		Mail::to($mails)->send(new TicketMail($ticket));
        	}



        	$error = false;
        	$message = [["Le message à bien été envoyé"]];
        	return response()->json(compact('error', 'message'), 200);
        }

        $error = true;
        $message = [['Une erreur est survenue']];
        return response()->json(compact('error', 'message'));


        
	}
}
