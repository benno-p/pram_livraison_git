<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilisateur;
use App\Observateur;
use Illuminate\Support\Facades\Log;

class DeveloppeurController extends Controller
{
    /*
     * Récupère les logs
     */
    public function logs()
    {
        $user = \Auth::user();

        $reg = '/\[(\d{4}\-\d{2}\-\d{2}\s\d{2}:\d{2}:\d{2})\]/';
        $reg2 = '/\[(\d{4}\-\d{2}\-\d{2}\T\d{2}:\d{2}:\d{2}).\d{6}\+\d{2}\:\d{2}\]/';
        $dossier_logs = storage_path('logs');
        $liste_fichier = scandir($dossier_logs);


        // return var_dump($liste_fichier);
        $logs = [];

        foreach ($liste_fichier as $fichier_log){
            if($fichier_log != '.' && $fichier_log != '..' && $fichier_log != '.gitignore' && strpos($fichier_log,'schedule') === false){
                $nom_fichier = str_replace('laravel-', '', $fichier_log);
                $nom_fichier = str_replace('.log', '', $nom_fichier);
                $logs[$nom_fichier]['nom'] = $nom_fichier;
                $logs[$nom_fichier]['nom_original'] = $fichier_log;

                $stream_fichier = fopen($dossier_logs.'/'.$fichier_log, 'r');
                while (!feof($stream_fichier)){
                    $ligne = fgets($stream_fichier);
                    // if (preg_match($reg, $ligne) || preg_match($reg2, $ligne)){
                    if (preg_match($reg, $ligne)){
                        $explode = explode(']', $ligne, 2);
                        $logs[$nom_fichier]['data'][] = [
                            'date' => utf8_encode(substr($explode[0], 1)),
                            'contenu' => utf8_encode($explode[1]),
                        ];
                    }
                    $logs[$nom_fichier]['data'] = array_reverse($logs[$nom_fichier]['data']);
                }
                usort($logs[$nom_fichier]['data'], array($this,'date_compare'));
                fclose($stream_fichier);
            }
        }

        return response()->json([
            'logs' => array_reverse($logs),
        ]);
    }



    private function date_compare($a, $b)
    {
        $t1 = strtotime($a['date']);
        $t2 = strtotime($b['date']);
        // return $t1 - $t2;
        return $t2 - $t1;
    }




    public function deleteLog(Request $request)
    {
        $dossier_logs = storage_path('logs');
        $liste_fichier = scandir($dossier_logs);
        unlink($dossier_logs.'/'.$request->fichier);
    }



    public function createCopieUserToObservateur()
    {
        $users = Utilisateur::all();

        foreach ($users as $user) {
            Observateur::updateOrCreate(
                ['nom' => $user->nom, 'prenom' => $user->prenom],
                ['nom' => ucfirst($user->nom), 'prenom' => ucfirst($user->prenom), 'utilisateur_id' => $user->id]
            );
        }

        return redirect('/');
    }


    public function writeJsLogs(Request $request)
    {
        Log::error($request->msg.' '.$request->trace.' URL: '.$request->route);
    }

}
