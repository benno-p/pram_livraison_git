<?php

namespace App\Http\Traits;

trait ErrorTrait {

    private $error;

    public function __construct()
    {
        $this->error = [
            'error' => true,
            'message' => "Une erreur est survenue"
        ];
    }


    private function success($model, $type, $data = []) // Exemple : $data['nom_de_la_variable' => $donnees]
    {
        $results = [
            'error' => false,
            'message' => $model." a bien été ".$type,
        ];

        if(!empty($data)){
            foreach ($data as $key => $value) {
                $results[$key] = $value;
            }
        }

        return $results;
    }
}