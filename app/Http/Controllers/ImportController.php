<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MaresImport;
use App\Imports\AuteursImport;

use App\Utilisateur;
use App\Observateur;
use App\Mare;

class ImportController extends Controller
{
    
    /*
	 * Importe les communes et fait la relation avec les départements
	 */
    public function importMares()
    {
    	$chemin_fichier = 'mares_1.xlsx';
    	$import = new MaresImport;
    	return $this->import($chemin_fichier, $import);
    }


    public function importAuteurs()
    {
        $chemin_fichier = 'auteur_origine.csv';
        $import = new AuteursImport;
        return $this->import($chemin_fichier, $import);
    }



    /*
     * Lance l'import
     */
    private function import($chemin_fichier, $import)
    {
    	$public_path = public_path('import');
    	$path = $public_path.'/'.$chemin_fichier;
    	$data = Excel::import($import, $path);
    	// return var_dump($data);

        $users = Utilisateur::all();

        foreach ($users as $user) {
            Observateur::updateOrCreate(
                ['utilisateur_id' => $user->id],
                ['nom' => ucfirst(strtolower($user->nom)), 'prenom' => ucfirst(strtolower($user->prenom)), 'utilisateur_id' => $user->id]
            );
        }

    	return redirect('/');
    }



    public function affectObservateurToMare()
    {
        $mares = Mare::where('auteur_origine', '!=', null)->get();

        $observateurs = Observateur::pluck('id', 'nom_source')->toArray();

        // var_dump($observateurs);

        // die();


        foreach ($mares as $mare) {
            if(array_key_exists($mare->auteur_origine, $observateurs)){
                // var_dump($observateurs[$mare->auteur_origine]);
                $mare->update(['observateur_id' => $observateurs[$mare->auteur_origine]]); 
            }


            // $observateur = Observateur::where('nom_source', '=', $mare->auteur_origine)->first();

            // if($observateur){
                
            //     $mare->update(['observateur_id' => $observateur->id]);
            // }
        }

        return redirect('/');


    }
}
