@if(array_key_exists('id', $options['mares_colonnes']) && $options['mares_colonnes']['id'] === true)
	<td>{{$mare->id ? $mare->id : ''}}</td>
@endif
@if(array_key_exists('x_l93', $options['mares_colonnes']) && $options['mares_colonnes']['x_l93'] === true)
	<td>{{$mare->x_l93 ? $mare->x_l93 : ''}}</td>
@endif
@if(array_key_exists('y_l93', $options['mares_colonnes']) && $options['mares_colonnes']['y_l93'] === true)
	<td>{{$mare->y_l93 ? $mare->y_l93 : ''}}</td>
@endif
@if(array_key_exists('lng', $options['mares_colonnes']) && $options['mares_colonnes']['lng'] === true)
	<td>{{$mare->lng ? $mare->lng : ''}}</td>
@endif
@if(array_key_exists('lat', $options['mares_colonnes']) && $options['mares_colonnes']['lat'] === true)
	<td>{{$mare->lat ? $mare->lat : ''}}</td>
@endif
@if(array_key_exists('nom', $options['mares_colonnes']) && $options['mares_colonnes']['nom'] === true)
	<td>{{$mare->nom ? $mare->nom : ''}}</td>
@endif
@if(array_key_exists('organisme_origine', $options['mares_colonnes']) && $options['mares_colonnes']['organisme_origine'] === true)
	<td>{{$mare->organisme_origine ? $mare->organisme_origine : ''}}</td>
@endif
@if(array_key_exists('id_origine', $options['mares_colonnes']) && $options['mares_colonnes']['id_origine'] === true)
	<td>{{$mare->id_origine ? $mare->id_origine : ''}}</td>
@endif
@if(array_key_exists('auteur_origine', $options['mares_colonnes']) && $options['mares_colonnes']['auteur_origine'] === true)
	<td>{{$mare->auteur_origine ? $mare->auteur_origine : ''}}</td>
@endif
@if(array_key_exists('annee_origine', $options['mares_colonnes']) && $options['mares_colonnes']['annee_origine'] === true)
	<td>{{$mare->annee_origine ? $mare->annee_origine : ''}}</td>
@endif
@if(array_key_exists('date_observation_origine', $options['mares_colonnes']) && $options['mares_colonnes']['date_observation_origine'] === true)
	<td>{{$mare->date_observation_origine ? $mare->date_observation_origine : ''}}</td>
@endif
@if(array_key_exists('departement_code', $options['mares_colonnes']) && $options['mares_colonnes']['departement_code'] === true)
	<td>{{$mare->departement_code ? $mare->departement_code : ''}}</td>
@endif
@if(array_key_exists('intercommunalite_siren', $options['mares_colonnes']) && $options['mares_colonnes']['intercommunalite_siren'] === true)
	<td>{{$mare->intercommunalite_siren ? $mare->intercommunalite_siren : ''}}</td>
@endif
@if(array_key_exists('commune_insee', $options['mares_colonnes']) && $options['mares_colonnes']['commune_insee'] === true)
	<td>{{$mare->commune_insee ? $mare->commune_insee : ''}}</td>
@endif
@if(array_key_exists('utilisateur', $options['mares_colonnes']) && $options['mares_colonnes']['utilisateur'] === true)
	<td>{{$mare->utilisateur_id && $mare->utilisateur ? $mare->utilisateur->nom.' '.$mare->utilisateur->prenom : ''}}</td>
@endif
@if(array_key_exists('type_observateur', $options['mares_colonnes']) && $options['mares_colonnes']['type_observateur'] === true)
	<td>{{$mare->type_observateur_id && $mare->type_observateur ? $mare->type_observateur->nom : ''}}</td>
@endif
@if(array_key_exists('type_propriete', $options['mares_colonnes']) && $options['mares_colonnes']['type_propriete'] === true)
	<td>{{$mare->type_propriete_id && $mare->type_propriete ? $mare->type_propriete->nom : ''}}</td>
@endif
@if(array_key_exists('situation_topographie', $options['mares_colonnes']) && $options['mares_colonnes']['situation_topographie'] === true)
	<td>{{$mare->situation_topographie_id && $mare->situation_topographie ? $mare->situation_topographie->nom : ''}}</td>
@endif
@if(array_key_exists('caracterisation', $options['mares_colonnes']) && $options['mares_colonnes']['caracterisation'] === true)
	<td>{{$mare->caracterisation_id && $mare->caracterisation ? $mare->caracterisation->nom : ''}}</td>
@endif
@if(array_key_exists('commentaire', $options['mares_colonnes']) && $options['mares_colonnes']['commentaire'] === true)
	<td>{{$mare->commentaire ? $mare->commentaire : ''}}</td>
@endif
@if(array_key_exists('commentaire_validation', $options['mares_colonnes']) && $options['mares_colonnes']['commentaire_validation'] === true)
	<td>{{$mare->commentaire_validation ? $mare->commentaire_validation : ''}}</td>
@endif
@if(array_key_exists('code_ofb', $options['mares_colonnes']) && $options['mares_colonnes']['code_ofb'] === true)
	<td>{{$mare->code_ofb ? $mare->code_ofb : ''}}</td>
@endif
@if(array_key_exists('statut', $options['mares_colonnes']) && $options['mares_colonnes']['statut'] === true)
	<td>{{$mare->statut_id && $mare->statut ? $mare->statut->nom : ''}}</td>
@endif
@if(array_key_exists('observateur', $options['mares_colonnes']) && $options['mares_colonnes']['observateur'] === true)
	<td>{{$mare->statut_id && $mare->observateur ? $mare->observateur->nom.' '.$mare->observateur->prenom  : ''}}</td>
@endif
@if(array_key_exists('date_saisie', $options['mares_colonnes']) && $options['mares_colonnes']['date_saisie'] === true)
	<td>{{$mare->created_at ? convertDate($mare->created_at) : ''}}</td>
@endif
@if(array_key_exists('annee_saisie', $options['mares_colonnes']) && $options['mares_colonnes']['annee_saisie'] === true)
	<td>{{$mare->created_at ? convertDateYear($mare->created_at) : ''}}</td>
@endif