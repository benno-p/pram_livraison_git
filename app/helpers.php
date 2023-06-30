<?php



function getCentroid($table, $column, $data)
{
	$query = "SELECT public.ST_X(public.st_centroid(public.st_transform($table.geom, 4326))) As coord_x, public.ST_Y(public.st_centroid(public.st_transform($table.geom, 4326))) As coord_y, $column 
    FROM pram.$table WHERE $column = '$data'";

    $centroide = DB::select(DB::raw($query));
    return $centroide[0];
}


function getGeoJsonFromSelectedFilter($table, $column, $data)
{
	$geojson = [];
	$query = "SELECT json_build_object(
        'type', 'FeatureCollection',
        'crs',  json_build_object(
            'type',      'name',
            'properties', json_build_object(
            'name', 'EPSG:4326')),
        'features', json_agg(
            json_build_object(
                'type',       'Feature',
                'id',         id,
                'geometry',   public.ST_AsGeoJSON(public.st_transform($table.geom,4326))::json,
                'properties', json_build_object(
                    'name', $column
                )
            )
        )
    ) AS objet_geojson FROM pram.$table WHERE $column = '$data'";


    $geojson = DB::select(DB::raw($query));

    if($geojson){
    	$geojson = json_decode($geojson[0]->objet_geojson);
    }

    return $geojson;
}



function updateFileUpload($modifier_image, $request, $dossier, $data_model, $model, $column_name)
{
    $chemin_fichier = public_path('configuration/');

    if($modifier_image === true && $request->image){
        // $image = $chemin_fichier.$dossier.'/'.$model->image;
        $image = $chemin_fichier.$dossier.'/'.$data_model;

        if(file_exists($image))unlink($image);

        $path = $request->image->store($model->id, $dossier);
        $model->update([$column_name => $path]);

    }elseif($modifier_image === true && $request->image === null){
        // $image = $chemin_fichier.$dossier.'/'.$model->image;
        $image = $chemin_fichier.$dossier.'/'.$data_model;

        // var_dump($image);

        if(file_exists($image))unlink($image);

        $model->update([$column_name => null]);

    }elseif($request->image){
        $path = $request->image->store($model->id, $dossier);
        $model->update([$column_name => $path]);
    }
}


function convertDate($date)
{
    return date('d/m/Y', strtotime($date));
}

function convertDateYear($date)
{
    return date('Y', strtotime($date));
}


function sanitizeManyDataExport($data)
{
    $string = '';
    foreach ($data as $value) {
        $string .= $value->nom.' | ';
    }
    return substr($string, 0, -3);
}


function export_data_mare($vue, $options, $mare)
{
    if(array_key_exists('id', $options['mares_colonnes']) && $options['mares_colonnes']['id'] === true){
        $vue['identifiant_mare'] =  $mare->id;
    }
    if(array_key_exists('x_l93', $options['mares_colonnes']) && $options['mares_colonnes']['x_l93'] === true){
        $vue['x_lambert_93'] = $mare->x_l93;
    }
    if(array_key_exists('y_l93', $options['mares_colonnes']) && $options['mares_colonnes']['y_l93'] === true){
        $vue['y_lambert_93'] = $mare->y_l93;
    }
    if(array_key_exists('lng', $options['mares_colonnes']) && $options['mares_colonnes']['lng'] === true){
        $vue['longitude'] = $mare->lng;
    }
    if(array_key_exists('lat', $options['mares_colonnes']) && $options['mares_colonnes']['lat'] === true){
        $vue['latitude'] = $mare->lat;
    }
    if(array_key_exists('nom', $options['mares_colonnes']) && $options['mares_colonnes']['nom'] === true){
        $vue['nom'] = $mare->nom;
    }
    if(array_key_exists('organisme_origine', $options['mares_colonnes']) && $options['mares_colonnes']['organisme_origine'] === true){
        $vue['organisme_origine'] = $mare->organisme_origine;
    }
    if(array_key_exists('id_origine', $options['mares_colonnes']) && $options['mares_colonnes']['id_origine'] === true){
        $vue['identifiant_origine'] = $mare->id_origine;
    }
    if(array_key_exists('auteur_origine', $options['mares_colonnes']) && $options['mares_colonnes']['auteur_origine'] === true){
        $vue['auteur_origine'] = $mare->auteur_origine;
    }
    if(array_key_exists('annee_origine', $options['mares_colonnes']) && $options['mares_colonnes']['annee_origine'] === true){
        $vue['annee_origine'] = $mare->annee_origine;
    }
    if(array_key_exists('date_observation_origine', $options['mares_colonnes']) && $options['mares_colonnes']['date_observation_origine'] === true){
        $vue['date_observation_origine'] = $mare->date_observation_origine;
    }
    if(array_key_exists('departement_code', $options['mares_colonnes']) && $options['mares_colonnes']['departement_code'] === true){
        $vue['code_departement'] = $mare->departement_code;
    }
    if(array_key_exists('intercommunalite_siren', $options['mares_colonnes']) && $options['mares_colonnes']['intercommunalite_siren'] === true){
        $vue['intercommunalite_siren'] = $mare->intercommunalite_siren;
    }
    if(array_key_exists('commune_insee', $options['mares_colonnes']) && $options['mares_colonnes']['commune_insee'] === true){
        $vue['commune_insee'] = $mare->commune_insee;
    }
    if(array_key_exists('utilisateur', $options['mares_colonnes']) && $options['mares_colonnes']['utilisateur'] === true){
        $vue['utilisateur'] = $mare->utilisateur_id && $mare->utilisateur ? $mare->utilisateur->nom. ' ' .$mare->utilisateur->prenom : '';
    }
    if(array_key_exists('type_observateur', $options['mares_colonnes']) && $options['mares_colonnes']['type_observateur'] === true){
        $vue['type_observateur'] = $mare->type_observateur_id && $mare->type_observateur ? $mare->type_observateur->nom : '';
    }
    if(array_key_exists('type_propriete', $options['mares_colonnes']) && $options['mares_colonnes']['type_propriete'] === true){
        $vue['type_propriete'] = $mare->type_propriete_id && $mare->type_propriete ? $mare->type_propriete->nom : '';
    }
    if(array_key_exists('situation_topographie', $options['mares_colonnes']) && $options['mares_colonnes']['situation_topographie'] === true){
        $vue['topographie'] = $mare->situation_topographie_id && $mare->situation_topographie ? $mare->situation_topographie->nom : '';
    }
    if(array_key_exists('caracterisation', $options['mares_colonnes']) && $options['mares_colonnes']['caracterisation'] === true){
        $vue['caracterisation'] = $mare->caracterisation_id && $mare->caracterisation ? $mare->caracterisation->nom : '';
    }
    if(array_key_exists('commentaire', $options['mares_colonnes']) && $options['mares_colonnes']['commentaire'] === true){
        $vue['commentaire'] = $mare->commentaire;
    }
    if(array_key_exists('commentaire_validation', $options['mares_colonnes']) && $options['mares_colonnes']['commentaire_validation'] === true){
        $vue['commentaire_validation'] = $mare->commentaire_validation;
    }
    if(array_key_exists('code_ofb', $options['mares_colonnes']) && $options['mares_colonnes']['code_ofb'] === true){
        $vue['code_ofb'] = $mare->code_ofb;
    }
    if(array_key_exists('statut', $options['mares_colonnes']) && $options['mares_colonnes']['statut'] === true){
        $vue['statut'] = $mare->statut_id && $mare->statut ? $mare->statut->nom : '';
    }
    if(array_key_exists('observateur', $options['mares_colonnes']) && $options['mares_colonnes']['observateur'] === true){
        $vue['observateur'] = $mare->observateur_id && $mare->observateur ? $mare->observateur->nom.' '.$mare->observateur->prenom : '';
    }
    if(array_key_exists('date_saisie', $options['mares_colonnes']) && $options['mares_colonnes']['date_saisie'] === true){
        $vue['date_saisie'] = $mare->created_at ? convertDate($mare->created_at) : '';
    }
    if(array_key_exists('annee_saisie', $options['mares_colonnes']) && $options['mares_colonnes']['annee_saisie'] === true){
        $vue['annee_saisie'] = $mare->created_at ? convertDateYear($mare->created_at) : '';
    }

    return $vue;
}


function export_data_fiche($vue, $options, $fiche)
{
    if(array_key_exists('id', $options['fiches_colonnes']) && $options['fiches_colonnes']['id'] === true){
        $vue['identifiant_fiche'] = $fiche->id;
    }
    if(array_key_exists('utilisateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['utilisateur'] === true){
        $vue['utilisateur_fiche'] = $fiche->utilisateur_id && $fiche->utilisateur ? $fiche->utilisateur->nom.' '.$fiche->utilisateur->prenom : '';
    }
    if(array_key_exists('observateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['observateur'] === true){
        $vue['observateur_fiche'] = $fiche->observateur_id && $fiche->observateur ? $fiche->observateur->nom. ' ' .$fiche->observateur->prenom : '';
    }
    if(array_key_exists('date_observation', $options['fiches_colonnes']) && $options['fiches_colonnes']['date_observation'] === true){
        $vue['date_observation'] = $fiche->date_observation ? convertDate($fiche->date_observation) : '';
    }
    if(array_key_exists('created_at', $options['fiches_colonnes']) && $options['fiches_colonnes']['created_at'] === true){
        $vue['saisie_le'] = $fiche->created_at ? convertDate($fiche->created_at) : '';
    }
    if(array_key_exists('type_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['type_mare'] === true){
        $vue['type_mare'] = $fiche->type_mare && $fiche->type_mare->nom ? $fiche->type_mare->nom : '';
    }
    if(array_key_exists('stade_evolution_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['stade_evolution_mare'] === true){
        $vue['stade_evolution_mare'] = $fiche->stade_evolution_mare && $fiche->stade_evolution_mare->nom ? $fiche->stade_evolution_mare->nom : '';
    }
    if(array_key_exists('usage_mares', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_mares'] === true){
        // $vue['usage_mares'] = sanitizeManyDataExport($fiche->usage_mares);
    }
    if(array_key_exists('pompe_a_nez', $options['fiches_colonnes']) && $options['fiches_colonnes']['pompe_a_nez'] === true){
        $vue['pompe_a_nez'] = $fiche->pompe_a_nez === true ? 'OUI' : 'NON';
    }
    if(array_key_exists('usage_dechets', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_dechets'] === true){
        // $vue['usage_dechets'] = sanitizeManyDataExport($fiche->usage_dechets);
    }
    if(array_key_exists('situation_contextes', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_contextes'] === true){
        $vue['contexte'] = sanitizeManyDataExport($fiche->situation_contextes);
    }
    if(array_key_exists('situation_batis', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_batis'] === true){
        // $vue['petit_patrimoine_bati_associe'] = sanitizeManyDataExport($fiche->situation_batis);
    }
    if(array_key_exists('situation_cloture', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_cloture'] === true){
        $vue['mare_cloture'] = $fiche->situation_cloture && $fiche->situation_cloture->nom ? $fiche->situation_cloture->nom : '';
    }
    if(array_key_exists('haie_contact_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['haie_contact_mare'] === true){
        $vue['haie_contact_mare'] = $fiche->haie_contact_mare === true ? 'OUI' : 'NON';
    }
    if(array_key_exists('caracteristique_forme', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_forme'] === true){
        $vue['forme'] = $fiche->caracteristique_forme && $fiche->caracteristique_forme->nom ? $fiche->caracteristique_forme->nom : '';
    }
    if(array_key_exists('caracteristique_eau_hauteur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_eau_hauteur'] === true){
        $vue['hauteur_eau'] = $fiche->caracteristique_eau_hauteur && $fiche->caracteristique_eau_hauteur->nom ? $fiche->caracteristique_eau_hauteur->nom : '';
    }
    if(array_key_exists('caracteristique_longueur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_longueur'] === true){
        $vue['longueur'] = $fiche->caracteristique_longueur ? $fiche->caracteristique_longueur : '';
    }
    if(array_key_exists('caracteristique_largeur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_largeur'] === true){
        $vue['largeur'] = $fiche->caracteristique_largeur ? $fiche->caracteristique_largeur : '';
    }
    if(array_key_exists('caracteristique_fond_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_fond_mare'] === true){
        $vue['nature_fond_mare'] = $fiche->caracteristique_fond_mare && $fiche->caracteristique_fond_mare->nom ? $fiche->caracteristique_fond_mare->nom : '';
    }
    if(array_key_exists('caracteristique_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_berge'] === true){
        $vue['berge_pente_douce'] = $fiche->caracteristique_berge && $fiche->caracteristique_berge->nom ? $fiche->caracteristique_berge->nom : '';
    }
    if(array_key_exists('caracteristique_curage_haut_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge'] === true){
        $vue['bourrelet_de_curage'] = $fiche->caracteristique_curage_haut_berge === true ? 'OUI' : 'NON';
    }
    if(array_key_exists('caracteristique_curage_haut_berge_texte', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge_texte'] === true){
        $vue['bourrelet_de_curage_pourcentage_perimetre'] = $fiche->caracteristique_curage_haut_berge_texte ? $fiche->caracteristique_curage_haut_berge_texte : '';
    }
    if(array_key_exists('caracteristique_pietinement', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_pietinement'] === true){
        $vue['surpietinement_abords'] = $fiche->caracteristique_pietinement && $fiche->caracteristique_pietinement->nom ? $fiche->caracteristique_pietinement->nom : '';
    }
    if(array_key_exists('hydrologie_regime', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_regime'] === true){
        $vue['regime_hydrologique'] = $fiche->hydrologie_regime && $fiche->hydrologie_regime->nom ? $fiche->hydrologie_regime->nom : '';
    }
    if(array_key_exists('hydrologie_reseaus', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_reseaus'] === true){
        // $vue['liaison_reseau_hydrographique'] = sanitizeManyDataExport($fiche->hydrologie_reseaus);
    }
    if(array_key_exists('hydrologie_alimentations', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_alimentations'] === true){
        // $vue['alimentation_specifique'] = sanitizeManyDataExport($fiche->hydrologie_alimentations);
    }
    if(array_key_exists('hydrologie_turbidite', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_turbidite'] === true){
        $vue['turbidite_eau'] = $fiche->hydrologie_turbidite && $fiche->hydrologie_turbidite->nom ? $fiche->hydrologie_turbidite->nom : '';
    }
    if(array_key_exists('couleur_specifique', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique'] === true){
        $vue['couleur_specifique'] = $fiche->couleur_specifique === true ? 'OUI' : 'NON';
    }
    if(array_key_exists('couleur_specifique_autre', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique_autre'] === true){
        $vue['precision_couleur'] = $fiche->couleur_specifique_autre ? $fiche->couleur_specifique_autre : '';
    }
    if(array_key_exists('hydrologie_tampon', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_tampon'] === true){
        $vue['zone_tampon'] = $fiche->hydrologie_tampon && $fiche->hydrologie_tampon->nom ? $fiche->hydrologie_tampon->nom : '';
    }
    if(array_key_exists('hydrologie_exutoire', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_exutoire'] === true){
        $vue['exutoire'] = $fiche->hydrologie_exutoire && $fiche->hydrologie_exutoire->nom ? $fiche->hydrologie_exutoire->nom : '';
    }
    if(array_key_exists('ecologie_helophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_helophytes'] === true){
        $vue['helophytes'] = $fiche->ecologie_helophytes ? $fiche->ecologie_helophytes : '';
    }
    if(array_key_exists('ecologie_hydrophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes'] === true){
        $vue['hydrophytes_enracines'] = $fiche->ecologie_hydrophytes ? $fiche->ecologie_hydrophytes : '';
    }
    if(array_key_exists('ecologie_hydrophytes_non_enracines', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes_non_enracines'] === true){
        $vue['hydrophytes_non_enracines'] = $fiche->ecologie_hydrophytes_non_enracines ? $fiche->ecologie_hydrophytes_non_enracines : '';
    }
    if(array_key_exists('ecologie_algues', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_algues'] === true){
        $vue['algues_filamenteuses'] = $fiche->ecologie_algues ? $fiche->ecologie_algues : '';
    }
    if(array_key_exists('ecologie_eau_libre', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_eau_libre'] === true){
        $vue['eau_libre_sans_vegetation_aquatique'] = $fiche->ecologie_eau_libre ? $fiche->ecologie_eau_libre : '';
    }
    if(array_key_exists('ecologie_fond_exonde', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_fond_exonde'] === true){
        $vue['fond_exonde_non_vegetalise'] = $fiche->ecologie_fond_exonde ? $fiche->ecologie_fond_exonde : '';
    }
    if(array_key_exists('ecologie_boisement', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_boisement'] === true){
        $vue['boisement'] = $fiche->ecologie_boisement && $fiche->ecologie_boisement->nom ? $fiche->ecologie_boisement->nom : '';
    }
    if(array_key_exists('ecologie_ombrage', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_ombrage'] === true){
        $vue['ombrage'] = $fiche->ecologie_ombrage && $fiche->ecologie_ombrage->nom ? $fiche->ecologie_ombrage->nom : '';
    }
    if(array_key_exists('faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['faunes'] === true){
        // $vue['faunes'] = sanitizeManyDataExport($fiche->faunes);
    }
    if(array_key_exists('flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['flores'] === true){
        // $vue['flores'] = sanitizeManyDataExport($fiche->flores);
    }
    if(array_key_exists('eee_faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_faunes'] === true){
        // $vue['eee_faunes'] = sanitizeManyDataExport($fiche->eee_faunes);
    }
    if(array_key_exists('eee_flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_flores'] === true){
        // $vue['eee_flores'] = sanitizeManyDataExport($fiche->eee_flores);
    }
    if(array_key_exists('interventions', $options['fiches_colonnes']) && $options['fiches_colonnes']['interventions'] === true){
        // $vue['interventions'] = sanitizeManyDataExport($fiche->interventions);
    }
    if(array_key_exists('intervenir_objectif', $options['fiches_colonnes']) && $options['fiches_colonnes']['intervenir_objectif'] === true){
        $vue['objectif_intervention'] = $fiche->intervenir_objectif ? $fiche->intervenir_objectif : '';
    }
    if(array_key_exists('remarque_generale', $options['fiches_colonnes']) && $options['fiches_colonnes']['remarque_generale'] === true){
        $vue['remarque_generale'] = $fiche->remarque_generale ? $fiche->remarque_generale : '';
    }
    if(array_key_exists('date_saisie_fiche', $options['fiches_colonnes']) && $options['fiches_colonnes']['date_saisie_fiche'] === true){
        $vue['date_saisie_fiche'] = $fiche->created_at ? convertDate($fiche->created_at) : '';
    }
    if(array_key_exists('annee_saisie_fiche', $options['fiches_colonnes']) && $options['fiches_colonnes']['annee_saisie_fiche'] === true){
        $vue['annee_saisie_fiche'] = $fiche->created_at ? convertDateYear($fiche->created_at) : '';
    }

    return $vue;
}