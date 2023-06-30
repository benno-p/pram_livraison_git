@if(array_key_exists('id', $options['fiches_colonnes']) && $options['fiches_colonnes']['id'] === true)
	<th>identifiant_fiche</th>
@endif
@if(array_key_exists('utilisateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['utilisateur'] === true)
	<th>utilisateur</th>
@endif
@if(array_key_exists('observateur', $options['fiches_colonnes']) && $options['fiches_colonnes']['observateur'] === true)
	<th>observateur</th>
@endif
@if(array_key_exists('date_observation', $options['fiches_colonnes']) && $options['fiches_colonnes']['date_observation'] === true)
	<th>date_observation</th>
@endif
@if(array_key_exists('created_at', $options['fiches_colonnes']) && $options['fiches_colonnes']['created_at'] === true)
	<th>saisie_le</th>
@endif
@if(array_key_exists('type_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['type_mare'] === true)
	<th>type_mare</th>
@endif
@if(array_key_exists('stade_evolution_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['stade_evolution_mare'] === true)
	<th>stade_evolution_mare</th>
@endif
@if(array_key_exists('usage_mares', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_mares'] === true)
	<th>usage_mares</th>
@endif
@if(array_key_exists('pompe_a_nez', $options['fiches_colonnes']) && $options['fiches_colonnes']['pompe_a_nez'] === true)
	<th>pompe_a_nez</th>
@endif
@if(array_key_exists('usage_dechets', $options['fiches_colonnes']) && $options['fiches_colonnes']['usage_dechets'] === true)
	<th>usage_dechets</th>
@endif
@if(array_key_exists('situation_contextes', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_contextes'] === true)
	<th>contexte</th>
@endif
@if(array_key_exists('situation_batis', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_batis'] === true)
	<th>petit_patrimoine_bati_associe</th>
@endif
@if(array_key_exists('situation_cloture', $options['fiches_colonnes']) && $options['fiches_colonnes']['situation_cloture'] === true)
	<th>mare_cloture</th>
@endif
@if(array_key_exists('haie_contact_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['haie_contact_mare'] === true)
	<th>haie_contact_mare</th>
@endif
@if(array_key_exists('caracteristique_forme', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_forme'] === true)
	<th>forme</th>
@endif
@if(array_key_exists('caracteristique_eau_hauteur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_eau_hauteur'] === true)
	<th>hauteur_eau</th>
@endif
@if(array_key_exists('caracteristique_longueur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_longueur'] === true)
	<th>longueur</th>
@endif
@if(array_key_exists('caracteristique_largeur', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_largeur'] === true)
	<th>largeur</th>
@endif
@if(array_key_exists('caracteristique_fond_mare', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_fond_mare'] === true)
	<th>nature_fond_mare</th>
@endif
@if(array_key_exists('caracteristique_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_berge'] === true)
	<th>berge_pente_douce</th>
@endif
@if(array_key_exists('caracteristique_curage_haut_berge', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge'] === true)
	<th>bourrelet_de_curage</th>
@endif
@if(array_key_exists('caracteristique_curage_haut_berge_texte', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_curage_haut_berge_texte'] === true)
	<th>bourrelet_de_curage_pourcentage_perimetre</th>
@endif
@if(array_key_exists('caracteristique_pietinement', $options['fiches_colonnes']) && $options['fiches_colonnes']['caracteristique_pietinement'] === true)
	<th>surpietinement_abords</th>
@endif
@if(array_key_exists('hydrologie_regime', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_regime'] === true)
	<th>regime_hydrologique</th>
@endif
@if(array_key_exists('hydrologie_reseaus', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_reseaus'] === true)
	<th>liaison_reseau_hydrographique</th>
@endif
@if(array_key_exists('hydrologie_alimentations', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_alimentations'] === true)
	<th>alimentation_specifique</th>
@endif
@if(array_key_exists('hydrologie_turbidite', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_turbidite'] === true)
	<th>turbidite_eau</th>
@endif
@if(array_key_exists('couleur_specifique', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique'] === true)
	<th>couleur_specifique</th>
@endif
@if(array_key_exists('couleur_specifique_autre', $options['fiches_colonnes']) && $options['fiches_colonnes']['couleur_specifique_autre'] === true)
	<th>precision_couleur</th>
@endif
@if(array_key_exists('hydrologie_tampon', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_tampon'] === true)
	<th>zone_tampon</th>
@endif
@if(array_key_exists('hydrologie_exutoire', $options['fiches_colonnes']) && $options['fiches_colonnes']['hydrologie_exutoire'] === true)
	<th>exutoire</th>
@endif
@if(array_key_exists('ecologie_helophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_helophytes'] === true)
	<th>helophytes</th>
@endif
@if(array_key_exists('ecologie_hydrophytes', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes'] === true)
	<th>hydrophytes_enracines</th>
@endif
@if(array_key_exists('ecologie_hydrophytes_non_enracines', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_hydrophytes_non_enracines'] === true)
	<th>hydrophytes_non_enracines</th>
@endif
@if(array_key_exists('ecologie_algues', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_algues'] === true)
	<th>algues_filamenteuses</th>
@endif
@if(array_key_exists('ecologie_eau_libre', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_eau_libre'] === true)
	<th>eau_libre_sans_vegetation_aquatique</th>
@endif
@if(array_key_exists('ecologie_fond_exonde', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_fond_exonde'] === true)
	<th>fond_exonde_non_vegetalise</th>
@endif
@if(array_key_exists('ecologie_boisement', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_boisement'] === true)
	<th>boisement</th>
@endif
@if(array_key_exists('ecologie_ombrage', $options['fiches_colonnes']) && $options['fiches_colonnes']['ecologie_ombrage'] === true)
	<th>ombrage</th>
@endif
@if(array_key_exists('faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['faunes'] === true)
	<th>faunes</th>
@endif
@if(array_key_exists('flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['flores'] === true)
	<th>flores</th>
@endif
@if(array_key_exists('eee_faunes', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_faunes'] === true)
	<th>eee_faunes</th>
@endif
@if(array_key_exists('eee_flores', $options['fiches_colonnes']) && $options['fiches_colonnes']['eee_flores'] === true)
	<th>eee_flores</th>
@endif
@if(array_key_exists('interventions', $options['fiches_colonnes']) && $options['fiches_colonnes']['interventions'] === true)
	<th>interventions</th>
@endif
@if(array_key_exists('intervenir_objectif', $options['fiches_colonnes']) && $options['fiches_colonnes']['intervenir_objectif'] === true)
	<th>objectif_intervention</th>
@endif
@if(array_key_exists('remarque_generale', $options['fiches_colonnes']) && $options['fiches_colonnes']['remarque_generale'] === true)
	<th>remarque_generale</th>
@endif