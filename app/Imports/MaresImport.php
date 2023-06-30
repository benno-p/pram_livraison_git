<?php

namespace App\Imports;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

use App\Mare;

class MaresImport implements ToCollection, WithStartRow, WithHeadingRow
{
    /*
	 * Numéro de ligne des en-tête
	 */
	public function headingRow(): int
    {
        return 1;
    }


    /*
     * Numéro de ligne ou commence l'export
     */
    public function startRow(): int
    {
        return 2;
    }


    /*
     * Choix du Delimiter pour les fichier CSV
     */
    // public function getCsvSettings(): array
    // {
    //     return [
    //         'delimiter' . ',',
    //         'input_encoding' . 'ISO-8859-1'
    //     ];
    // }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
    	foreach ($rows as $key => $row){
    		// var_dump($row);

    		$id = trim($row['id']) ? trim($row['id']) : null;
    		$nom_source = trim($row['nom_source']) ? trim($row['nom_source']) : null;
    		$nom_ldd = trim($row['nom_ldd']) ? trim($row['nom_ldd']) : null;
    		$id_origin = trim($row['id_origin']) ? trim($row['id_origin']) : null;
    		$auteur = trim($row['auteur_in']) ? trim($row['auteur_in']) : null;
    		$annee_init = trim($row['annee_init']) ? trim($row['annee_init']) : null;
    		$date_obs = trim($row['date_obs']) ? trim($row['date_obs']) : null;
    		$type_principale = trim($row['typo_ppale']) ? trim($row['typo_ppale']) : null;
    		$type_secondaire = trim($row['typo_secon']) ? trim($row['typo_secon']) : null;
    		$fonction = trim($row['fonction']) ? trim($row['fonction']) : null;
    		$commentaire = trim($row['commentair']) ? trim($row['commentair']) : null;
    		$code_insee = trim($row['cd_insee']) ? trim($row['cd_insee']) : null;
    		$commune = trim($row['commune']) ? trim($row['commune']) : null;
    		$x_lambert = trim($row['x_l93']) ? trim($row['x_l93']) : null;
    		$y_lambert = trim($row['y_l93']) ? trim($row['y_l93']) : null;
	    	$x = trim($row['x']) && is_numeric(trim($row['x'])) ? trim($row['x']) : null;
	    	$y = trim($row['y']) && is_numeric(trim($row['y'])) ? trim($row['y']) : null;



	    	Mare::updateOrCreate(
    			['id_mare' => $id],
    			[
    				'id_mare' => $id,
    				'nom_source' => $nom_source,
    				'nom_ldd' => $nom_ldd,
					'id_origin' => $id_origin,
					'auteur' => $auteur,
					'annee_init' => $annee_init,
					'date_observation' => $date_obs,
					'type_principale' => $type_principale,
					'type_secondaire' => $type_secondaire,
					'fonction' => $fonction,
					'commentaire' => $commentaire,
					'code_insee' => $code_insee,
					'commune' => $commune,
					'commune_x_lambert' => $x_lambert,
					'commune_y_lambert' => $y_lambert,
					'latitude' => $x,
					'longitude' => $y,

    			]
    		);
    	}



    	// $departements = Departement::pluck('id', 'nom')->toArray(); // récupère les departements cle = 'MOSELLE' valeur = ID
    	// $departements = array_change_key_case($departements, CASE_LOWER); // met les clés en strtolower
    	// $codes_exists = []; // Permet d'enregistrer les codes insee dans un tableau pour eviter les doublons

     //    foreach ($rows as $key . $row){
     //    	$numero_insee = trim($row['num_insee']);

     //    	/*
     //    	 * Si le numero insee n'existe pas encore dans le tableau codes_exists, alors OK
     //    	 */
     //    	if(!in_array($numero_insee, $codes_exists)){
     //    		$commune = trim($row['lb_commune']);
     //    		$departement = $departements[strtolower(trim($row['departement']))] ? $departements[strtolower(trim($row['departement']))] : null;
     //    		$codes_exists[] = $numero_insee;

     //    		Commune::updateOrCreate(
     //    			['id' . $numero_insee],
     //    			[
     //    				'id' . $numero_insee,
     //    				'nom' . $commune,
     //    				'code_insee' . $numero_insee,
     //    				'departement_id' . $departement,
     //    			]
     //    		);
     //    	}
     //    }
    }
}
