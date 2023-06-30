@if(array_key_exists('id', $options['fiches_colonnes']) && $options['fiches_colonnes']['id'] === true)
	<td>{{$fiche->id ? $fiche->id : ''}}</td>
@endif
@if(array_key_exists('utilisateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['utilisateur'] === true)
	<td>{{$fiche->utilisateur_id && $fiche->utilisateur ? $fiche->utilisateur->nom.' '.$fiche->utilisateur->prenom : ''}}</td>
@endif
@if(array_key_exists('observateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['observateur'] === true)
	<td>{{$fiche->observateur_id && $fiche->observateur ? $fiche->observateur->nom.' '.$fiche->observateur->prenom : ''}}</td>
@endif
@if(array_key_exists('date_observation', $options['fiches_colonnes']) && $options['fiches_colonnes']['date_observation'] === true)
	<td>{{$fiche->date_observation ? $fiche->date_observation : ''}}</td>
@endif
@if(array_key_exists('created_at', $options['fiches_colonnes']) && $options['fiches_colonnes']['created_at'] === true)
	<td>{{$fiche->created_at ? convertDate($fiche->created_at) : ''}}</td>
@endif
@if(array_key_exists('type_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['type_mare'] === true)
	<td>{{$fiche->type_mare && $fiche->type_mare->nom ? $fiche->type_mare->nom : ''}}</td>
@endif
@if(array_key_exists('stade_evolution_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['stade_evolution_mare'] === true)
	<td>{{$fiche->stade_evolution_mare && $fiche->stade_evolution_mare->nom ? $fiche->stade_evolution_mare->nom : ''}}</td>
@endif
@if(array_key_exists('usage_mares', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_mares'] === true)
	<td>{{sanitizeManyDataExport($fiche->usage_mares)}}</td>
@endif
@if(array_key_exists('pompe_a_nez', $options['fiches_colonnes']) && $options['fiches_colonnes']['pompe_a_nez'] === true)
	<td>{{$fiche->pompe_a_nez === true ? 'OUI' : 'NON'}}</td>
@endif
@if(array_key_exists('usage_dechets', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_dechets'] === true)
	<td>{{sanitizeManyDataExport($fiche->usage_dechets)}}</td>
@endif
@if(array_key_exists('situation_contextes', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_contextes'] === true)
	<td>{{sanitizeManyDataExport($fiche->situation_contextes)}}</td>
@endif
@if(array_key_exists('situation_batis', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_batis'] === true)
	<td>{{sanitizeManyDataExport($fiche->situation_batis)}}</td>
@endif
@if(array_key_exists('situation_cloture', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_cloture'] === true)
	<td>{{$fiche->situation_cloture && $fiche->situation_cloture->nom ? $fiche->situation_cloture->nom : ''}}</td>
@endif
@if(array_key_exists('haie_contact_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['haie_contact_mare'] === true)
	<td>{{$fiche->haie_contact_mare === true ? 'OUI' : 'NON'}}</td>
@endif
@if(array_key_exists('caracteristique_forme', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_forme'] === true)
	<td>{{$fiche->caracteristique_forme && $fiche->caracteristique_forme->nom ? $fiche->caracteristique_forme->nom : ''}}</td>
@endif
@if(array_key_exists('caracteristique_eau_hauteur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_eau_hauteur'] === true)
	<td>{{$fiche->caracteristique_eau_hauteur && $fiche->caracteristique_eau_hauteur->nom ? $fiche->caracteristique_eau_hauteur->nom : ''}}</td>
@endif
@if(array_key_exists('caracteristique_longueur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_longueur'] === true)
	<td>{{$fiche->caracteristique_longueur ? $fiche->caracteristique_longueur : ''}}</td>
@endif
@if(array_key_exists('caracteristique_largeur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_largeur'] === true)
	<td>{{$fiche->caracteristique_largeur ? $fiche->caracteristique_largeur : ''}}</td>
@endif
@if(array_key_exists('caracteristique_fond_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_fond_mare'] === true)
	<td>{{$fiche->caracteristique_fond_mare && $fiche->caracteristique_fond_mare->nom ? $fiche->caracteristique_fond_mare->nom : ''}}</td>
@endif
@if(array_key_exists('caracteristique_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_berge'] === true)
	<td>{{$fiche->caracteristique_berge && $fiche->caracteristique_berge->nom ? $fiche->caracteristique_berge->nom : ''}}</td>
@endif
@if(array_key_exists('caracteristique_curage_haut_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge'] === true)
	<td>{{$fiche->caracteristique_curage_haut_berge === true ? 'OUI' : 'NON'}}</td>
@endif
@if(array_key_exists('caracteristique_curage_haut_berge_texte', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge_texte'] === true)
	<td>{{$fiche->caracteristique_curage_haut_berge_texte ? $fiche->caracteristique_curage_haut_berge_texte : ''}}</td>
@endif
@if(array_key_exists('caracteristique_pietinement', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_pietinement'] === true)
	<td>{{$fiche->caracteristique_pietinement && $fiche->caracteristique_pietinement->nom ? $fiche->caracteristique_pietinement->nom : ''}}</td>
@endif
@if(array_key_exists('hydrologie_regime', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_regime'] === true)
	<td>{{$fiche->hydrologie_regime && $fiche->hydrologie_regime->nom ? $fiche->hydrologie_regime->nom : ''}}</td>
@endif
@if(array_key_exists('hydrologie_reseaus', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_reseaus'] === true)
	<td>{{sanitizeManyDataExport($fiche->hydrologie_reseaus)}}</td>
@endif
@if(array_key_exists('hydrologie_alimentations', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_alimentations'] === true)
	<td>{{sanitizeManyDataExport($fiche->hydrologie_alimentations)}}</td>
@endif
@if(array_key_exists('hydrologie_turbidite', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_turbidite'] === true)
	<td>{{$fiche->hydrologie_turbidite && $fiche->hydrologie_turbidite->nom ? $fiche->hydrologie_turbidite->nom : ''}}</td>
@endif
@if(array_key_exists('couleur_specifique', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique'] === true)
	<td>{{$fiche->couleur_specifique === true ? 'OUI' : 'NON'}}</td>
@endif
@if(array_key_exists('couleur_specifique_autre', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique_autre'] === true)
	<td>{{$fiche->couleur_specifique_autre ? $fiche->couleur_specifique_autre : ''}}</td>
@endif
@if(array_key_exists('hydrologie_tampon', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_tampon'] === true)
	<td>{{$fiche->hydrologie_tampon && $fiche->hydrologie_tampon->nom ? $fiche->hydrologie_tampon->nom : ''}}</td>
@endif
@if(array_key_exists('hydrologie_exutoire', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_exutoire'] === true)
	<td>{{$fiche->hydrologie_exutoire && $fiche->hydrologie_exutoire->nom ? $fiche->hydrologie_exutoire->nom : ''}}</td>
@endif
@if(array_key_exists('ecologie_helophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_helophytes'] === true)
	<td>{{$fiche->ecologie_helophytes ? $fiche->ecologie_helophytes : ''}}</td>
@endif
@if(array_key_exists('ecologie_hydrophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes'] === true)
	<td>{{$fiche->ecologie_hydrophytes ? $fiche->ecologie_hydrophytes : ''}}</td>
@endif
@if(array_key_exists('ecologie_hydrophytes_non_enracines', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes_non_enracines'] === true)
	<td>{{$fiche->ecologie_hydrophytes_non_enracines ? $fiche->ecologie_hydrophytes_non_enracines : ''}}</td>
@endif
@if(array_key_exists('ecologie_algues', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_algues'] === true)
	<td>{{$fiche->ecologie_algues ? $fiche->ecologie_algues : ''}}</td>
@endif
@if(array_key_exists('ecologie_eau_libre', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_eau_libre'] === true)
	<td>{{$fiche->ecologie_eau_libre ? $fiche->ecologie_eau_libre : ''}}</td>
@endif
@if(array_key_exists('ecologie_fond_exonde', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_fond_exonde'] === true)
	<td>{{$fiche->ecologie_fond_exonde ? $fiche->ecologie_fond_exonde : ''}}</td>
@endif
@if(array_key_exists('ecologie_boisement', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_boisement'] === true)
	<td>{{$fiche->ecologie_boisement && $fiche->ecologie_boisement->nom ? $fiche->ecologie_boisement->nom : ''}}</td>
@endif
@if(array_key_exists('ecologie_ombrage', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_ombrage'] === true)
	<td>{{$fiche->ecologie_ombrage && $fiche->ecologie_ombrage->nom ? $fiche->ecologie_ombrage->nom : ''}}</td>
@endif
@if(array_key_exists('faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['faunes'] === true)
	<td>{{sanitizeManyDataExport($fiche->faunes)}}</td>
@endif
@if(array_key_exists('flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['flores'] === true)
	<td>{{sanitizeManyDataExport($fiche->flores)}}</td>
@endif
@if(array_key_exists('eee_faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_faunes'] === true)
	<td>{{sanitizeManyDataExport($fiche->eee_faunes)}}</td>
@endif
@if(array_key_exists('eee_flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_flores'] === true)
	<td>{{sanitizeManyDataExport($fiche->eee_flores)}}</td>
@endif
@if(array_key_exists('interventions', $options['fiches_colonnes']) && $options['fiches_colonnes']['interventions'] === true)
	<td>{{sanitizeManyDataExport($fiche->interventions)}}</td>
@endif
@if(array_key_exists('intervenir_objectif', $options['fiches_colonnes']) && $options['fiches_colonnes']['intervenir_objectif'] === true)
	<td>{{$fiche->intervenir_objectif ? $fiche->intervenir_objectif : ''}}</td>
@endif
@if(array_key_exists('remarque_generale', $options['fiches_colonnes']) && $options['fiches_colonnes']['remarque_generale'] === true)
	<td>{{$fiche->remarque_generale ? $fiche->remarque_generale : ''}}</td>
@endif