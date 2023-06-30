<?php

namespace App\Imports;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

use App\Utilisateur;
use App\Observateur;


class AuteursImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row){
            $explode = explode(' ', $row[0]);
            $nom = '';
            $prenom = 'N/C';
            $user = [];

            // var_dump($row);

            if(!empty($explode[0])){
                $nom = trim(strtolower($explode[0]));
            }

            if(!empty($explode[1])){
                $prenom = trim(strtolower($explode[1]));
            }


            $user = Utilisateur::whereRaw('lower(nom) like (?)', ["{$nom}"])
                ->whereRaw('lower(prenom) like (?)', ["{$prenom}"])
                ->first();

            if(empty($user)){
                $user = Utilisateur::whereRaw('lower(nom) like (?)', ["{$prenom}"])
                ->whereRaw('lower(prenom) like (?)', ["{$nom}"])
                ->first();
            }

            if($nom === 'miranda' && $prenom === "d'assuncao"){
                $user = Utilisateur::where('email', '=', 'mmdassuncao@hotmail.com')->first();
            }

            if($nom === 'cpie' && $prenom === "55"){
                $user = Utilisateur::where('email', '=', 'sarah.wolf@cpie-meuse.fr')->first();
            }


            $observateur = Observateur::create([
                'nom' => ucfirst($nom),
                'prenom' => ucfirst($prenom),
                'nom_source' => $row[0],
                'utilisateur_id' => !empty($user) ? $user->id : null
            ]);

            // var_dump($observateur);


            // var_dump($nom.' '.$prenom);
            // var_dump($user);
            // echo '<hr>';

        }
    }
}
