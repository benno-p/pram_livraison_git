<template>
	<div>
		<div class="w-100 pt-4 pb-4" id="mareComponent" v-bind:style="{ height: computedHeight }" v-if="!dataLoad">
			<div class="container-fluid">
			    <div class="row">
			        <div class="col-12">
			            <div class="card mb-4 pb-4">
			                <div class="card-header">
			                	<button type="button" class="close" aria-label="Close" @click="closeMareComponent()">
						        	<span aria-hidden="true">&times;</span>
						        </button>
			                	Consulter une mare
			                </div>

			                <div class="card-body">
			                    <form method="POST" v-on:submit.prevent="create()" enctype="multipart/form-data">
			                    	<div class="card">
			                			<div class="card-header bg-dark text-light">
			                    			<h4 class="font-weight-bold">Données générales</h4>
			                    		</div>
			                    		<div class="card-body">
					                    	<div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label class="col-12 col-form-label text-cen-bleu">Saisie par<span class="text-danger"><b>*</b></span></label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" readonly="readonly" :value="utilisateur.nom + ' '+ utilisateur.prenom">
						                            </div>
						                        </div>

						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Observateur<span class="text-danger"><b>*</b></span> :</label>
			                                        <div class="d-flex flex-wrap">
				                                        <div class="col-8">
				                                            <multiselect
				                                            ref="observateur_selected"
				                                            v-model="observateur_selected" 
				                                            :options="observateurs"  
				                                            placeholder="Recherche par nom ou prénom" 
				                                            label="label" 
				                                            track-by="id"
				                                            selectLabel=""
				                                            deselectLabel=""
				                                            selectedLabel="Selectionner"
				                                            >
				                                            </multiselect>
				                                        </div>
				                                        <div>
				                                        	<button class="btn btn-success" @click.prevent="createObservateur()">
				                                        		<i class="fas fa-plus-circle"></i> Créer un observateur
				                                        	</button>
				                                        </div>
				                                    </div>
						                        </div>
						                    </div>
						                    <div class="form-group row">
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Type d'observateur<span class="text-danger"><b>*</b></span> :</label>
			                                        <div class="col-12">
			                                            <multiselect 
			                                            ref="type_observateur"
			                                            v-model="type_observateur" 
			                                            :options="type_observateurs"  
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label class="col-12 col-form-label text-cen-bleu">Source de la donnée</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" v-model="nom_mare">
						                            </div>
						                        </div>
						                        <div class="col-12 col-md-3">
						                            <label class="col-12 col-form-label text-cen-bleu">X Lambert93</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" v-model="infoNouvelleMare.x_lambert93" readonly="readonly">
						                            </div>
						                        </div>
						                        <div class="col-12 col-md-3">
						                            <label class="col-12 col-form-label text-cen-bleu">Y Lambert93</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" v-model="infoNouvelleMare.y_lambert93" readonly="readonly">
						                            </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Type de propriété<span class="text-danger"><b>*</b></span></label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            ref="type_propriete"
			                                            v-model="type_propriete" 
			                                            :options="type_proprietes" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Topographie<span class="text-danger"><b>*</b></span></label>
			                                        <div class="col-12">
			                                            <multiselect 
			                                            ref="situation_topographie"
			                                            v-model="situation_topographie" 
			                                            :options="situation_topographies" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-2">
						                            <label class="col-12 col-form-label text-cen-bleu">Departement</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" :value="infoNouvelleMare.dep ? infoNouvelleMare.dep : ''" readonly="readonly">
						                            </div>
						                        </div>
						                        <div class="col-12 col-md-3">
						                            <label class="col-12 col-form-label text-cen-bleu">Intercommunalité</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" :value="infoNouvelleMare.raison_soc ? infoNouvelleMare.raison_soc : ''" readonly="readonly">
						                            </div>
						                        </div>
					                        	<div class="col-12 col-md-3">
						                            <label class="col-12 col-form-label text-cen-bleu">Commune</label>
						                            <div class="col-12">
						                                <input type="text" class="form-control" :value="infoNouvelleMare.nom ? infoNouvelleMare.nom : ''" readonly="readonly">
						                            </div>
						                        </div>
						                        <div class="col-12 col-md-2">
						                            <label class="col-12 col-form-label text-cen-bleu">Code OFB</label>
						                            <div class="col-12">
						                                <input 
						                                v-if="checkIfUserCanChangeInput('code_ofb')"
						                                type="text" 
						                                class="form-control"
						                                v-model="code_ofb"
						                                >

						                                <input 
						                                v-else
						                                type="text" 
						                                class="form-control"
						                                disabled="disabled"
						                                readonly="readonly"
						                                >
						                            </div>
						                        </div>

						                        <div class="col-12 col-md-2" v-if="checkIfUserCanChangeInput('statut_protection')">
						                        	<label class="col-12 col-form-label text-cen-bleu">Statut protection</label>
						                            <div class="col-12">
							                        	<multiselect 
				                                            ref="statut_protection"
				                                            v-model="statut_protection" 
				                                            :options="statut_protections" 
				                                            placeholder="Sélection" 
				                                            label="nom" 
				                                            track-by="id"
				                                            selectLabel=""
				                                            deselectLabel=""
				                                            selectedLabel="Selectionner"
				                                            >
				                                        </multiselect>
				                                    </div>
						                        </div>
					                        </div>
					                    </div>
					                </div>
					                <div class="card mt-4">
			                			<div class="card-header bg-dark text-light">
			                				<h4 class="font-weight-bold">Fiche</h4>
			                			</div>
			                			<div class="card-body">
					                        <h4 class="text-cen-orange font-weight-bold">Usages</h4>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Type de mares<span class="text-danger"><b>*</b></span></label>
			                                        <div class="col-12">
			                                            <multiselect 
			                                            ref="type_mare"
			                                            v-model="type_mare" 
			                                            :options="type_mares" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>

						                        <div class="col-12 col-md-4">
			                                        <label for="date_observation" class="col-12 col-form-label">Date d'observation sur le terrain<span class="text-danger"><b>*</b></span></label>
			                                        <div class="col-12 input-group">
			                                            <vuejs-datepicker 
			                                            ref="dp_observation" 
			                                            :language="$fr"
			                                            :bootstrap-styling="true" 
			                                            class="form-control p-perso-09 h-100"
			                                            placeholder="jj/mm/AAAA"
			                                            :highlighted="$highlighted"
			                                            :clear-button="true"
			                                            v-model="date_observation"
			                                            :disabled-dates="disabledDates"
			                                            >
			                                            </vuejs-datepicker>
			                                            <div class="input-group-append iconCalendar" v-on:click="datePickerOpened('dp_observation', $refs)">
			                                                <span class="btn btn-primary">
			                                                    <i class="fas fa-calendar-alt"></i>
			                                                </span>
			                                            </div>
			                                        </div>
			                                    </div>
					                        </div>
					                        <div class="form-group row p-4">
					                        	<div class="col-12 col-md-12 p-0">
						                        	<label for="nom" class="col-form-label text-cen-bleu">Stade d'évolution de la mare</label>
						                        </div>
					                        	<div class="col-12 col-md-2 text-center border p-0 mt-1" v-for="stade in stade_evolution_mares">
					                        		<label :for="'radio_stade_'+stade.id">
					                        			<img v-bind:src="stade.chemin_image" class="img-fluid p-4"/>
					                        		</label>
					                        		<div class="col-12">
						                        		<div class="form-check form-check-inline">
										                    <input class="form-check-input" type="radio" :id="'radio_stade_'+stade.id" :value="stade.id" v-model="stade_evolution_mare">
										                    <label class="form-check-label" :for="'radio_stade_'+stade.id">{{stade.nom}}</label>
										                </div>
										                <div class="col-12">
										                	<label :for="'radio_stade_'+stade.id">{{stade.descriptif}}</label>
										                </div>
										            </div>
					                        	</div>
					                        </div>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Usage(s) observé(s) de la mare</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="usage in usage_mares" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_usage' + usage.id" :value="usage.id" v-model="usage_mare" :disabled="disabledNonRenseigneInput('usage_mares', 'usage_mare', usage.id) || disabledIfMareDisparueSelected()" 
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_usage' + usage.id">{{usage.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="form-group row">
						                        <div class="col-12 col-md-12 mt-1">
									                <label for="nom" class="col-12 col-form-label text-cen-bleu">Pompe à nez</label>
									                <div class="col-12">
										                <div class="form-check form-check-inline">
										                    <input class="form-check-input" type="radio" id="radio_pompe_nez_oui" :value="1" v-model="pompe_a_nez" :disabled="disabledIfMareDisparueSelected()">
										                    <label class="form-check-label" for="radio_pompe_nez_oui">Oui</label>
										                </div>
										                <div class="form-check form-check-inline">
										                    <input class="form-check-input" type="radio" id="radio_pompe_nez_non" :value="0" v-model="pompe_a_nez" :disabled="disabledIfMareDisparueSelected()">
										                    <label class="form-check-label" for="radio_pompe_nez_non">Non</label>
										                </div>
										            </div>
									            </div>
					                        </div>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Présence de déchets</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="dechet in usage_dechets" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_dechet' + dechet.id" :value="dechet.id" v-model="usage_dechet" :disabled="disabledNonRenseigneInput('usage_dechets', 'usage_dechet', dechet.id)" 
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_dechet' + dechet.id">{{dechet.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>

					                        <hr>

					                        <h4 class="text-cen-orange font-weight-bold">Situation</h4>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Contexte</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="contexte in situation_contextes" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_contexte' + contexte.id" :value="contexte.id" v-model="situation_contexte" :disabled="disabledNonRenseigneInput('situation_contextes', 'situation_contexte', contexte.id) || disabledIfMareDisparueSelected()" 
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_contexte' + contexte.id">{{contexte.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>

			                                <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Petit patrimoine bâti associé</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="bati in situation_batis" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_bati' + bati.id" :value="bati.id" v-model="situation_bati" :disabled="disabledNonRenseigneInput('situation_batis', 'situation_bati', bati.id) || disabledIfMareDisparueSelected()"
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_bati' + bati.id">{{bati.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>

			                                <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Mare cloturée</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="situation_cloture" 
			                                            :options="situation_clotures" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
									                <label for="nom" class="col-12 col-form-label text-cen-bleu">Présence d'une haie en contact avec la mare</label>
									                <div class="col-12">
										                <div class="form-check form-check-inline">
										                    <input class="form-check-input" type="radio" id="radio_pompe_haie_oui" :value="1" v-model="haie_contact_mare" :disabled="disabledIfMareDisparueSelected()">
										                    <label class="form-check-label" for="radio_pompe_haie_oui">Oui</label>
										                </div>
										                <div class="form-check form-check-inline">
										                    <input class="form-check-input" type="radio" id="radio_pompe_haie_non" :value="0" v-model="haie_contact_mare" :disabled="disabledIfMareDisparueSelected()">
										                    <label class="form-check-label" for="radio_pompe_haie_non">Non</label>
										                </div>
										            </div>
						                        </div>
					                        </div>

					                        <hr>

					                        <h4 class="text-cen-orange font-weight-bold">Caractéristiques abiotiques de la mare</h4>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Forme</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="caracteristique_forme" 
			                                            :options="caracteristique_formes" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Hauteur d'eau maximum observée aujourd'hui</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="caracteristique_eau_hauteur" 
			                                            :options="caracteristique_eau_hauteurs" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label class="col-12 col-form-label text-cen-bleu">Longueur de la mare (moyenne en mètre)</label>
						                            <div class="col-12">
						                                <input type="number" step="0.01" class="form-control" v-model="longueur_mare" :disabled="disabledIfMareDisparueSelected()">
						                            </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label class="col-12 col-form-label text-cen-bleu">Largeur de la mare (moyenne en mètre)</label>
						                            <div class="col-12">
						                                <input type="number" step="0.01" class="form-control" v-model="largeur_mare" :disabled="disabledIfMareDisparueSelected()">
						                            </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Nature du fond de la mare</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="caracteristique_fond_mare" 
			                                            :options="caracteristique_fond_mares" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Berges en pente douce</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="caracteristique_berge" 
			                                            :options="caracteristique_berges" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
						                        <div class="col-12 col-md-6 d-flex">
						                        	<div class="col-12 col-md-6">
										                <label for="nom" class="col-12 col-form-label text-cen-bleu">Bourrelet de curage en haut de berge</label>
										                <div class="col-12">
											                <div class="form-check form-check-inline">
											                    <input class="form-check-input" type="radio" id="bourrelet_curage_oui" :value="1" v-model="caracteristique_curage_haut_berge" :disabled="disabledIfMareDisparueSelected()">
											                    <label class="form-check-label" for="bourrelet_curage_oui">Oui</label>
											                </div>
											                <div class="form-check form-check-inline">
											                    <input class="form-check-input" type="radio" id="bourrelet_curage_non" :value="0" v-model="caracteristique_curage_haut_berge" :disabled="disabledIfMareDisparueSelected()">
											                    <label class="form-check-label" for="bourrelet_curage_non">Non</label>
											                </div>
											            </div>
											        </div>
											        <div class="col-12 col-md-6">
											            <div class="col-12  p-0" v-if="caracteristique_curage_haut_berge == 1">
								                            <label class="col-12 col-form-label text-cen-bleu">Si oui, % du périmètre de la mare</label>
								                            <div class="col-12 input-group">
								                                <input type="number" step="0.01" class="form-control" v-model="caracteristique_curage_haut_berge_texte" :disabled="disabledIfMareDisparueSelected()">
								                                <div class="input-group-append">
								                                    <span class="input-group-text">%</span>
								                                </div>
								                            </div>
											            </div>
											        </div>
						                        </div>

						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Surpiétinement des abords</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="caracteristique_pietinement" 
			                                            :options="caracteristique_pietinements" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                    </div>

					                   
					                        <hr>

					                        <h4 class="text-cen-orange font-weight-bold">Hydrologie</h4>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Régime hydrologique</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="hydrologie_regime" 
			                                            :options="hydrologie_regimes" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
					                        </div>
					                        <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Liaison(s) avec le réseau hydrographique superficiel</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="reseau in hydrologie_reseaux" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_reseau' + reseau.id" :value="reseau.id" v-model="hydrologie_reseau" :disabled="disabledNonRenseigneInput('hydrologie_reseaux', 'hydrologie_reseau', reseau.id) || disabledIfMareDisparueSelected()"
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_reseau' + reseau.id">{{reseau.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>

			                                <div class="form-group row">
					                        	<div class="col-12 col-md-12">
					                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Alimentation spécifique</label>
			                                        <div class="d-flex flex-wrap col-12">
			                                            <div v-for="alimentation in hydrologie_alimentations" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
			                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_alimentation' + alimentation.id" :value="alimentation.id" v-model="hydrologie_alimentation" :disabled="disabledNonRenseigneInput('hydrologie_alimentations', 'hydrologie_alimentation', alimentation.id) || disabledIfMareDisparueSelected()"
			                                                >
			                                                <label class="custom-control-label" :for="'checkbox_alimentation' + alimentation.id">{{alimentation.nom}}</label>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>

			                                <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Turbidité de l'eau</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="hydrologie_turbidite" 
			                                            :options="hydrologie_turbidites" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>

						                        <div class="col-12 col-md-6 d-flex">
					                        		<div class="col-12 col-md-6">
										                <label for="nom" class="col-12 col-form-label text-cen-bleu">L'eau a une couleur spécifique</label>
										                <div class="col-12">
											                <div class="form-check form-check-inline">
											                    <input class="form-check-input" type="radio" id="eau_couleur_specifique_oui" :value="1" v-model="couleur_specifique" :disabled="disabledIfMareDisparueSelected()">
											                    <label class="form-check-label" for="eau_couleur_specifique_oui">Oui</label>
											                </div>
											                <div class="form-check form-check-inline">
											                    <input class="form-check-input" type="radio" id="eau_couleur_specifique_non" :value="0" v-model="couleur_specifique" :disabled="disabledIfMareDisparueSelected()">
											                    <label class="form-check-label" for="eau_couleur_specifique_non">Non</label>
											                </div>
											            </div>
											        </div>
											        <div class="col-12 col-md-6">
											            <div class="col-12 p-0" v-if="couleur_specifique == 1">
								                            <label class="col-12 col-form-label text-cen-bleu">Si oui, précisez</label>
								                            <div class="col-12">
								                                <input type="text" class="form-control" v-model="couleur_specifique_autre" :disabled="disabledIfMareDisparueSelected()">
								                            </div>
											            </div>
											        </div>
						                        </div>
					                        </div>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Zone tampon</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="hydrologie_tampon" 
			                                            :options="hydrologie_tampons" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Exutoire</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="hydrologie_exutoire" 
			                                            :options="hydrologie_exutoires" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                    </div>

					                        <hr>

					                        <h4 class="text-cen-orange font-weight-bold">Écologie</h4>
					                        <vegetation-component v-if="disabledIfMareDisparueSelected() === false"
					                        v-on:dataFromVegetationComponent="dataFromVegetationComponent"
					                        ></vegetation-component>
					                        

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Boisement / embroussaillement des abords</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="ecologie_boisement" 
			                                            :options="ecologie_boisements" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                        <div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Ombrage surface par ligneux (soleil au zénith)</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="ecologie_ombrage" 
			                                            :options="ecologie_ombrages" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                    </div>

						                    <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Présence de poisson</label>
			                                        <div class="col-12">
			                                            <multiselect
			                                            :disabled="disabledIfMareDisparueSelected()"
			                                            v-model="hydrologie_presence_poisson" 
			                                            :options="hydrologie_presence_poissons" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>
						                    </div>

						                    <template v-if="disabledIfMareDisparueSelected() === false">
						                    	<div class="card ml-3 mr-3">
						                    		<div class="card-header">
						                    			<button class="btn btn-success float-right" @click.prevent="addEspeceComponent(faune_components, 'faune-component', 'id_faune_component', id_faune_component++, 'taxon_faune', 'faune')">
										                	<i class="fas fa-plus-circle"></i> Ajouter une espèce
										                </button>
						                    			<b>Observation Faune</b>
						                    		</div>
													<div class="card-body">
														<template v-if="faune_components.length == 0">
															<p>Aucune espèce renseignée</p>
														</template>
														<template v-else v-for="fc in faune_components">
															<faune-component 
															:ref="'faune_component_ref_' + fc.id_faune_component"
															:key="'faune_component_key_' + fc.id_faune_component"
															:is="faune_component"
															:taxonFaunesProps="taxon_faunes"
															:idFauneComponentProps="fc.id_faune_component"
															v-on:deleteFauneComponent="deleteFauneComponent"
															v-on:dataFromFauneComponent="dataFromFauneComponent"
															>
															</faune-component>
														</template>
								                    </div>
								                </div>

								                <div class="card ml-3 mr-3 mt-4">
						                    		<div class="card-header">
						                    			<button class="btn btn-success float-right" @click.prevent="addEspeceComponent(flore_components, 'flore-component', 'id_flore_component', id_flore_component++, 'taxon_flore', 'flore')">
										                	<i class="fas fa-plus-circle"></i> Ajouter une espèce
										                </button>
						                    			<b>Observation Flore</b>
						                    		</div>
													<div class="card-body">
														<template v-if="flore_components.length == 0">
															<p>Aucune espèce renseignée</p>
														</template>
														<template v-else v-for="fc in flore_components">
															<flore-component 
															:ref="'flore_component_ref_' + fc.id_flore_component"
															:key="'flore_component_key_' + fc.id_flore_component"
															:is="flore_component"
															:taxonFloresProps="taxon_flores"
															:idFloreComponentProps="fc.id_flore_component"
															v-on:deleteFloreComponent="deleteFloreComponent"
															v-on:dataFromFloreComponent="dataFromFloreComponent"
															>
															</flore-component>
														</template>
								                    </div>
								                </div>

								                <div class="card ml-3 mr-3 mt-4">
						                    		<div class="card-header">
						                    			<button class="btn btn-success float-right" @click.prevent="addEspeceComponent(eee_faune_components, 'eee-faune-component', 'id_eee_faune_component', id_eee_faune_component++, 'taxon_eee_faune', 'eee_faune')">
										                	<i class="fas fa-plus-circle"></i> Ajouter une espèce
										                </button>
						                    			<b>Espèce(s) animale(s) exotique(s) envahissante(s) observée(s)</b>
						                    		</div>
													<div class="card-body">
														<template v-if="eee_faune_components.length == 0">
															<p>Aucune espèce renseignée</p>
														</template>
														<template v-else v-for="ef in eee_faune_components">
															<eee-faune-component
															:ref="'eee_faune_component_ref_' + ef.id_eee_faune_component"
															:key="'eee_faune_component_key_' + ef.id_eee_faune_component"
															:is="eee_faune_component"
															:taxonEeeFaunesProps="taxon_eee_faunes"
															:idEeeFauneComponentProps="ef.id_eee_faune_component"
															v-on:deleteEeeFauneComponent="deleteEeeFauneComponent"
															v-on:dataFromEeeFauneComponent="dataFromEeeFauneComponent"
															>
															</eee-faune-component>
														</template>
								                    </div>
								                </div>
								                <div class="card ml-3 mr-3 mt-4">
						                    		<div class="card-header">
						                    			<button class="btn btn-success float-right" @click.prevent="addEspeceComponent(eee_flore_components, 'eee-flore-component', 'id_eee_flore_component', id_eee_flore_component++, 'taxon_eee_flore', 'eee_flore')">
										                	<i class="fas fa-plus-circle"></i> Ajouter une espèce
										                </button>
						                    			<b>Espèce(s) végétale(s) exotique(s) envahissante(s) observée(s)</b>
						                    		</div>
													<div class="card-body">
														<template v-if="eee_flore_components.length == 0">
															<p>Aucune espèce renseignée</p>
														</template>
														<template v-else v-for="ef in eee_flore_components">
															<eee-flore-component
															:ref="'eee_flore_component_ref_' + ef.id_eee_flore_component"
															:key="'eee_flore_component_key_' + ef.id_eee_flore_component"
															:is="eee_flore_component"
															:taxonEeeFloresProps="taxon_eee_flores"
															:idEeeFloreComponentProps="ef.id_eee_flore_component"
															v-on:deleteEeeFloreComponent="deleteEeeFloreComponent"
															v-on:dataFromEeeFloreComponent="dataFromEeeFloreComponent"
															>
															</eee-flore-component>
														</template>
								                    </div>
								                </div>

						                        <hr>

						                        <h4 class="text-cen-orange font-weight-bold">Intervenir en faveur de cette mare</h4>
						                        <div class="form-group row">
						                        	<div class="col-12 col-md-12">
						                        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Travaux à envisager</label>
				                                        <div class="d-flex flex-wrap col-12">
				                                            <div v-for="travaux in interventions" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
				                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_travaux' + travaux.id" :value="travaux.id" v-model="intervention" :disabled="disabledNonRenseigneInput('interventions', 'intervention', travaux.id)" 
				                                                >
				                                                <label class="custom-control-label" :for="'checkbox_travaux' + travaux.id">{{travaux.nom}}</label>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>

				                                <div class="form-group row">
						                        	<div class="col-12 col-md-6">
							                            <label class="col-12 col-form-label text-cen-bleu">Dans quel(s) Objectif(s)</label>
							                            <div class="col-12">
							                                <textarea class="form-control w-100" rows="5" v-model="intervenir_objectif"></textarea>
							                            </div>
							                        </div>
						                        </div>
						                    </template>

					                        <hr>

					                        <h4 class="text-cen-orange font-weight-bold">Photos</h4>
					                        <label for="create_annee">Photo</label>
					                        <div class="custom-file">
												<input type="file" class="custom-file-input" id="customFile" ref="photo" @change="handleFileUpload()" accept=".jpg,.JPG,.jpeg,.JPEG,.png,.PNG">
												<label class="custom-file-label" for="customFile" id="label_fichier">Choisir une photo (format jpg, jpeg ou png)</label>
											</div>

					                        <hr>

					                        <div class="form-group row">
					                        	<div class="col-12 col-md-6">
						                            <label class="col-12 col-form-label text-cen-bleu">Remarques générales</label>
						                            <div class="col-12">
						                                <textarea class="form-control w-100" rows="5" v-model="remarque_generale"></textarea>
						                            </div>
						                        </div>

						                        <div class="col-12 col-md-6" v-if="can_change_caracterisation">
						                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Caractérisation<span class="text-danger"><b>*</b></span></label>
			                                        <div class="col-12">
			                                            <multiselect 
			                                            ref="caracterisation_selected"
			                                            v-model="caracterisation_selected" 
			                                            :options="caracterisations" 
			                                            placeholder="Sélection" 
			                                            label="nom" 
			                                            track-by="id"
			                                            selectLabel=""
			                                            deselectLabel=""
			                                            selectedLabel="Selectionner"
			                                            >
			                                            </multiselect>
			                                        </div>
						                        </div>

					                        </div>

					                        <hr>

					                        <div class="form-group row mb-0">
					                            <div class="col-12">
					                                <button type="submit" class="btn btn-success">
					                                    Enregistrer
					                                </button>
					                                <button class="btn btn-secondary" aria-label="Close" @click.prevent="closeMareComponent()">
								        				Quitter
								        			</button>
					                            </div>
					                        </div>
					                    </div>
					                </div>
			                    </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>

		<router-view 
		name="modal"
		:before_route="before_route"
		v-on:addNewObservateur="addNewObservateur"
		>
		</router-view>

	</div>
</template>

<script>
	import FauneComponent from './taxon_components/FauneComponent.vue';
	import FloreComponent from './taxon_components/FloreComponent.vue';
	import EeeFauneComponent from './taxon_components/EeeFauneComponent.vue';
	import EeeFloreComponent from './taxon_components/EeeFloreComponent.vue';
	export default{
		components: {
			'faune-component' : FauneComponent,
			'flore-component' : FloreComponent,
			'eee-faune-component' : EeeFauneComponent,
			'eee-flore-component' : EeeFloreComponent,
		},
		props:{
			infoNouvelleMareProps: false,
			latitudeNouvelleMare: false,
			longitudeNouvelleMare: false,
		},
		data(){
			return{
				isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,

				height:'94vh',
				infoNouvelleMare: this.infoNouvelleMareProps,
				latitude_nouvelle_mare: this.latitudeNouvelleMare,
				longitude_nouvelle_mare: this.longitudeNouvelleMare,
				isLoading: true,
				dataLoad: true,
				nom_mare: '',
				errors: [],
				utilisateur: [],
				type_observateur: [],
				type_observateurs: [],
				type_propriete: [],
				type_proprietes: [],
				type_mare: [],
				type_mares: [],
				stade_evolution_mare: null,
				stade_evolution_mares: [],
				situation_topographie :[],
				situation_topographies: [],
				usage_mare: [],
				usage_mares: [],
				usage_dechet: [],
				usage_dechets: [],
				situation_contexte: [],
				situation_contextes: [],
				situation_bati: [],
				situation_batis: [],
				situation_cloture: [],
				situation_clotures: [],
				caracteristique_forme: [],
				caracteristique_formes: [],
				caracteristique_eau_hauteur: [],
				caracteristique_eau_hauteurs: [],
				caracteristique_fond_mare : [],
				caracteristique_fond_mares: [],
				caracteristique_berge: [],
				caracteristique_berges: [],
				caracteristique_pietinement: [],
				caracteristique_pietinements: [],
				hydrologie_regime: [],
				hydrologie_regimes: [],
				hydrologie_reseau: [],
				hydrologie_reseaux: [],
				hydrologie_alimentation: [],
				hydrologie_alimentations: [],
				hydrologie_turbidite: [],
				hydrologie_turbidites: [],
				hydrologie_tampon: [],
				hydrologie_tampons: [],
				hydrologie_exutoire: [],
				hydrologie_exutoires: [],
				hydrologie_presence_poisson: [],
				hydrologie_presence_poissons: [],

				ecologie_boisement: [],
				ecologie_boisements: [],
				ecologie_ombrage: [],
				ecologie_ombrages: [],
				intervention: [],
				interventions: [],
				intervenir_objectif: '',
				taxon_faunes: [],
				taxon_flores: [],
				taxon_eee_faunes: [],
				taxon_eee_flores: [],

				faune_components: [],
				faune_component: 'faune-component',
				id_faune_component: 0,

				flore_components: [],
				flore_component: 'flore-component',
				id_flore_component: 0,

				eee_faune_components: [],
				eee_faune_component: 'eee-faune-component',
				id_eee_faune_component: 0,

				eee_flore_components: [],
				eee_flore_component: 'eee-flore-component',
				id_eee_flore_component: 0,


				couleur_specifique: 0,
				couleur_specifique_autre: '',
				caracteristique_curage_haut_berge: 0,
				caracteristique_curage_haut_berge_texte: '',
				longueur_mare: '',
				largeur_mare: '',
				haie_contact_mare: 0,
				pompe_a_nez: 0,
				base_url:base_url,

				/*
				 * Model pour vegetation component
				 */
				ecologie_helophytes: null,
				ecologie_hydrophytes: null,
				ecologie_hydrophytes_non_enracines: null,
				ecologie_algues: null,
				ecologie_eau_libre: null,
				ecologie_fond_exonde: null,
				total_vegetation: '',

				photo: '',
				remarque_generale: '',
				date_observation: '',
				code_ofb: '',

				observateurs: [],
				observateur_selected: [],
				before_route: '',

				statut_protection: [],
				statut_protections: [],

				formulaires: [],
				can_change_caracterisation: false,
				caracterisations: [],
				caracterisation_selected: [],

				disabledDates: {
					from: new Date(moment())
				}
			}
		},
		computed: {
	    	computedHeight: function () {
	      		return this.height;
	    	},
	  	},
	  	watch:{
	  		isLoading: function(val){
	  			this.$emit('checkIfIsLoading', val);
	  		},
	  		// stade_evolution_mare: function(){

	  		// }
	  	},
		methods:{
			disabledIfMareDisparueSelected: function(){
				if(this.stade_evolution_mare){
					let stade = this.stade_evolution_mares.find(s => s.id === this.stade_evolution_mare);

					if(stade && stade.nom && stade.nom === 'Stade 5'){
						return true;
					}
				}
				return false;
			},
			createObservateur: function(){
				this.before_route = this.$route.path;
				this.$router.push({path : '/carte/create/observateur'});
			},
			addNewObservateur: function(event){
				this.observateurs.push(event);
				this.observateur_selected = this.observateurs.find(o => o.id === event.id);
			},
			checkIfUserCanChangeInput: function(input){
				if(this.utilisateur && this.utilisateur.role && this.utilisateur.role.nom_interne && (this.utilisateur.role.nom_interne == 'gestionnaire' || this.utilisateur.role.nom_interne == 'developpeur' || this.utilisateur.role.nom_interne == 'administrateur')){
					return true;
				}

				let form = this.formulaires.find(f => f.nom_interne === input);
				if(form && form.groupes && this.utilisateur.groupe && this.utilisateur.groupe.id){
					let autorisation = form.groupes.find(g => g.id === this.utilisateur.groupe.id);
					if(autorisation != undefined){
						return true;
					}
				}

				return false;
			},
			disabledNonRenseigneInput: function(models_name, model_name, id){
				let non_renseigne = this[models_name].find(m => m.nom == 'Non renseigné');

				if(non_renseigne){
					let in_array = this[model_name].find(m => m == non_renseigne.id);

					if(in_array != undefined){
						let index = this[model_name].findIndex(i => i == non_renseigne.id);
						if(this[model_name].length > 1){
							this[model_name].splice(index, 1);
						}
					}

					if(this[model_name].length > 0){
						if(id == non_renseigne.id){
							return true;
						}
					}else if(this[model_name].length == 0){
						this[model_name].push(non_renseigne.id);
					}
				}				
			},
			handleFileUpload(){
		        this.photo = this.$refs.photo.files[0];
		      	if(this.photo.name){
                    document.getElementById('label_fichier').innerHTML = this.photo.name;
                }
		    },
			dataFromVegetationComponent: function(event){
				this.ecologie_helophytes = event.ecologie_helophytes;
				this.ecologie_hydrophytes = event.ecologie_hydrophytes;
				this.ecologie_hydrophytes_non_enracines = event.ecologie_hydrophytes_non_enracines;
				this.ecologie_algues = event.ecologie_algues;
				this.ecologie_eau_libre = event.ecologie_eau_libre;
				this.ecologie_fond_exonde = event.ecologie_fond_exonde;
				this.total_vegetation = event.total_vegetation;
			},
			create: function(){
				let self = this;
                let formData = new FormData();
                self.isLoading = true;
                self.errors = [];

                this.resetColorInputError(this.$refs);

                if(!self.infoNouvelleMare || self.infoNouvelleMare.length == 0 || !self.latitude_nouvelle_mare || self.latitude_nouvelle_mare == '' || !self.longitude_nouvelle_mare || self.longitude_nouvelle_mare == ''){
                	self.errors.push('Un problème est survenu, aucune coordonnée n\'a pu être générée');
                }

                // self.infoNouvelleMare.forEach(function(nouvelle_mare){
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.dep == ""){
                		self.errors.push('Un problème est survenu, aucun département n\'a pu être récupéré');
                	}
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.geom == ""){
                		self.errors.push('Un problème est survenu, aucune coordonnée n\'a pu être générée');
                	}
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.insee == ""){
                		self.errors.push('Un problème est survenu, aucune commune n\'a pu être récupérée');
                	}
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.siren == ""){
                		self.errors.push('Un problème est survenu, aucune intercommunalité n\'a pu être récupérée');
                	}
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.x_lambert93 == ""){
                		self.errors.push('Un problème est survenu, aucune coordonnée n\'a pu être générée');
                	}
                	if(!self.infoNouvelleMare || self.infoNouvelleMare.y_lambert93 == ""){
                		self.errors.push('Un problème est survenu, aucune coordonnée n\'a pu être générée');
                	}
                // });

                if(self.objectSize(self.observateur_selected) == 0){
                	this.colorInputError(this.$refs, 'observateur_selected');
                    self.errors.push('Le champ Observateur est obligatoire');
                }

                if(!self.type_observateur || self.type_observateur.length == 0){
                	this.colorInputError(this.$refs, 'type_observateur');
                    self.errors.push('Le champ Type d\'observateur est obligatoire');
                }

                if(!self.situation_topographie || self.situation_topographie.length == 0){
                	this.colorInputError(this.$refs, 'situation_topographie');
                    self.errors.push('Le champ Topographie est obligatoire');
                }

                if(self.objectSize(self.type_propriete) == 0){
                	this.colorInputError(this.$refs, 'type_propriete');
                    self.errors.push('Le champ Type de propriété est obligatoire');
                }

                // Usage
                if(!self.type_mare || self.type_mare.length == 0){
                	this.colorInputError(this.$refs, 'type_mare');
                    self.errors.push('Le champ Type de mare est obligatoire');
                }

                if(!self.date_observation || !self.date_observation === ''){
                	this.colorInputError(this.$refs, 'dp_observation');
                    self.errors.push('Le champ Date d\'observation sur le terrain est obligatoire');
                }

                if(self.can_change_caracterisation === true && self.objectSize(self.caracterisation_selected) === 0){
                	this.colorInputError(this.$refs, 'caracterisation_selected');
                    self.errors.push('Le champ Caractérisation est obligatoire');
                }
                     
                if(self.errors.length){
                    self.showErrors(self.errors);
                    self.isLoading = false;
                    return false;
                }


                /*
                 * Mare données générales
                 */
                formData.append('caracterisation_id', self.caracterisation_selected && self.caracterisation_selected.id ? self.caracterisation_selected.id : '');
                formData.append('nom_mare', self.nom_mare ? self.nom_mare : '');
                formData.append('observateur_id', self.observateur_selected && self.observateur_selected.id ? self.observateur_selected.id : '');
                formData.append('type_observateur_id', self.type_observateur && self.type_observateur.id ? self.type_observateur.id : '');
                formData.append('type_propriete_id', self.type_propriete && self.type_propriete.id ? self.type_propriete.id : '');
                formData.append('situation_topographie_id', self.situation_topographie && self.situation_topographie.id ? self.situation_topographie.id : '');
                formData.append('code_ofb', self.code_ofb ? self.code_ofb : '');
                formData.append('statut_protection_id', self.statut_protection && self.statut_protection.id ? self.statut_protection.id : '');

                /*
                 * Mare fiche
                 */
                 // Usage
                formData.append('type_mare_id', self.type_mare && self.type_mare.id ? self.type_mare.id : '');
                formData.append('stade_evolution_mare_id', self.stade_evolution_mare ? self.stade_evolution_mare : '');

                self.usage_mare.forEach(function(usage){
                	formData.append('usage_mare[]', usage);
                });

                formData.append('pompe_a_nez', self.pompe_a_nez ? self.pompe_a_nez : 0);

                self.usage_dechet.forEach(function(dechet){
                	formData.append('usage_dechet[]', dechet);
                });

                // Situation
                self.situation_contexte.forEach(function(contexte){
                	formData.append('contexte[]', contexte);
                });

                self.situation_bati.forEach(function(bati){
                	formData.append('bati[]', bati);
                });


                formData.append('situation_cloture_id', self.situation_cloture && self.situation_cloture.id ? self.situation_cloture.id : '');

                formData.append('haie_contact_mare', self.haie_contact_mare ? self.haie_contact_mare : 0);

                // Caractéristique
                formData.append('caracteristique_forme_id', self.caracteristique_forme && self.caracteristique_forme.id ? self.caracteristique_forme.id : '');
                formData.append('caracteristique_eau_hauteur_id', self.caracteristique_eau_hauteur && self.caracteristique_eau_hauteur.id ? self.caracteristique_eau_hauteur.id : '');
                formData.append('caracteristique_longueur', self.longueur_mare ? self.longueur_mare : '');
                formData.append('caracteristique_largeur', self.largeur_mare ? self.largeur_mare : '');
                formData.append('caracteristique_fond_mare_id', self.caracteristique_fond_mare && self.caracteristique_fond_mare.id ? self.caracteristique_fond_mare.id : '');
                formData.append('caracteristique_berge_id', self.caracteristique_berge && self.caracteristique_berge.id ? self.caracteristique_berge.id : '');
                formData.append('caracteristique_curage_haut_berge', self.caracteristique_curage_haut_berge ? self.caracteristique_curage_haut_berge : 0);
                formData.append('caracteristique_curage_haut_berge_texte', self.caracteristique_curage_haut_berge_texte ? self.caracteristique_curage_haut_berge_texte : '');
                formData.append('caracteristique_pietinement_id', self.caracteristique_pietinement && self.caracteristique_pietinement.id ? self.caracteristique_pietinement.id : '');

                // Hydrologie
                formData.append('hydrologie_regime_id', self.hydrologie_regime && self.hydrologie_regime.id ? self.hydrologie_regime.id : '');
                
                self.hydrologie_reseau.forEach(function(reseau){
                	formData.append('reseau[]', reseau);
                });

                self.hydrologie_alimentation.forEach(function(alimentation){
                	formData.append('alimentation[]', alimentation);
                });

                formData.append('hydrologie_turbidite_id', self.hydrologie_turbidite && self.hydrologie_turbidite.id ? self.hydrologie_turbidite.id : '');
                formData.append('couleur_specifique', self.couleur_specifique ? self.couleur_specifique : 0);
                formData.append('couleur_specifique_autre', self.couleur_specifique_autre ? self.couleur_specifique_autre : '');
                formData.append('hydrologie_tampon_id', self.hydrologie_tampon && self.hydrologie_tampon.id ? self.hydrologie_tampon.id : '');
                formData.append('hydrologie_exutoire_id', self.hydrologie_exutoire && self.hydrologie_exutoire.id ? self.hydrologie_exutoire.id : '');
                formData.append('hydrologie_presence_poisson_id', self.hydrologie_presence_poisson && self.hydrologie_presence_poisson.id ? self.hydrologie_presence_poisson.id : '');

                // Ecologie

                formData.append('ecologie_boisement_id', self.ecologie_boisement && self.ecologie_boisement.id ? self.ecologie_boisement.id : '');
                formData.append('ecologie_ombrage_id', self.ecologie_ombrage && self.ecologie_ombrage.id ? self.ecologie_ombrage.id : '');
                formData.append('ecologie_helophytes', self.ecologie_helophytes ? self.ecologie_helophytes : '');
                formData.append('ecologie_hydrophytes', self.ecologie_hydrophytes ? self.ecologie_hydrophytes : '');
                formData.append('ecologie_hydrophytes_non_enracines', self.ecologie_hydrophytes_non_enracines ? self.ecologie_hydrophytes_non_enracines : '');
                formData.append('ecologie_algues', self.ecologie_algues ? self.ecologie_algues : '');
                formData.append('ecologie_eau_libre', self.ecologie_eau_libre ? self.ecologie_eau_libre : '');
                formData.append('ecologie_fond_exonde', self.ecologie_fond_exonde ? self.ecologie_fond_exonde : '');


                self.faune_components.forEach(function(fc){
                	if(fc.faune && fc.faune.id){
	                	let array = {}
	                	array.id = fc.faune.id;
	                	array.nombre = fc.nombre != '' ? fc.nombre : null;
	                	formData.append('faune[]', JSON.stringify(array));
                	}
                });

                self.flore_components.forEach(function(fc){
                	if(fc.flore && fc.flore.id){
                		let array = {}
                		array.id = fc.flore.id;
                		formData.append('flore[]', JSON.stringify(array));
                	}
                });

                self.eee_faune_components.forEach(function(efc){
                	if(efc.eee_faune && efc.eee_faune.id){
	                	let array = {}
	                	array.id = efc.eee_faune.id;
	                	array.nombre = efc.nombre != '' ? efc.nombre : null;
	                	formData.append('eee_faune[]', JSON.stringify(array));
                	}
                });

                self.eee_flore_components.forEach(function(efc){
                	if(efc.eee_flore && efc.eee_flore.id){
	                	let array = {}
	                	array.id = efc.eee_flore.id;
	                	array.pourcentage = efc.pourcentage ? efc.pourcentage : null;
	                	formData.append('eee_flore[]', JSON.stringify(array));
                	}
                });

                // Intervention
                self.intervention.forEach(function(intervention){
                	formData.append('intervention[]', intervention);
                });

                formData.append('intervenir_objectif', self.intervenir_objectif ? self.intervenir_objectif : '');
                formData.append('remarque_generale', self.remarque_generale ? self.remarque_generale : '');
                formData.append('date_observation', self.date_observation ? moment(self.date_observation).format('YYYY-MM-DD') : '');

                // self.infoNouvelleMare.forEach(function(nouvelle_mare){
            	formData.append('departement_code', self.infoNouvelleMare.dep ? self.infoNouvelleMare.dep : '');
            	formData.append('geom', self.infoNouvelleMare.geom ? self.infoNouvelleMare.geom : '');
            	formData.append('commune_insee', self.infoNouvelleMare.insee ? self.infoNouvelleMare.insee : '');
            	formData.append('intercommunalite_siren', self.infoNouvelleMare.siren ? self.infoNouvelleMare.siren : '');
				formData.append('x_lambert93', self.infoNouvelleMare.x_lambert93 ? self.infoNouvelleMare.x_lambert93 : '');
				formData.append('y_lambert93', self.infoNouvelleMare.y_lambert93 ? self.infoNouvelleMare.y_lambert93 : '');	
                // });

                formData.append('latitude', self.latitude_nouvelle_mare ? self.latitude_nouvelle_mare : '');
                formData.append('longitude', self.longitude_nouvelle_mare ? self.longitude_nouvelle_mare : '');

                formData.append('photo', self.photo ? self.photo : '');

                axios.post(base_url + '/mares', formData, { 
                    _method: 'POST',
                })
                .then(function (response) {
                    if(response.data.error == true){
                        Object.keys(response.data.message).map(function(objectKey, index) {
                            let value = response.data.message[objectKey];
                            self.errors.push(value[0]);
                        });
                        self.showErrors(self.errors);
                        self.isLoading = false;
                        return false;
                    }else{
						if(response.status === 200){
	                        self.showSuccess([response.data.message]);
	                        self.$router.push({path: '/carte'});
	                        self.isLoading = false;
	                        return self.$emit('checkIfIsLoading', false);
                    	}
                    }
                })
                .catch(function (error) { 
                    self.isLoading = false;
                    return self.checkError(error); 
                });
			},


			/*
			 * Ajoute un composant espece en fonction des parametres
			 */
			addEspeceComponent: function(components, component, id_name_component, id_component, taxon, espece){
				let object = {};
				object['component'] = component;
				object[id_name_component] = id_component++;
				object[taxon] = [];
				object[espece] = [];
				object['nombre'] = '';
				object['pourcentage'] = '';

				components.push(object);
			},

			/*
			 * Supprime un composant espece en fonction des parametres
			 */
			deleteEspeceComponent: function(event, components, id_name_component){
				let index = components.findIndex(c => Number(c[id_name_component]) == Number(event));
				if(index || index == 0){
					components.splice(index, 1);
				}
			},

			deleteFauneComponent: function(event){
				this.deleteEspeceComponent(event, this.faune_components, 'id_faune_component');
			},
			deleteFloreComponent: function(event){
				this.deleteEspeceComponent(event, this.flore_components, 'id_flore_component');
			},
			deleteEeeFauneComponent: function(event){
				this.deleteEspeceComponent(event, this.eee_faune_components, 'id_eee_faune_component');
			},
			deleteEeeFloreComponent: function(event){
				this.deleteEspeceComponent(event, this.eee_flore_components, 'id_eee_flore_component');
			},


			/*
			 * Récupère les données des composants especes en fonction des parametres
			 */
			dataFromComponent: function(event, components, id_name_component, taxon_name, espece_name){
				let component = components.find(c => c[id_name_component] == event[id_name_component]);
				if(component){
					component[taxon_name] = event[taxon_name];
					component[espece_name] = event[espece_name];
					component.nombre = event.nombre;
					component.pourcentage = event.pourcentage;
				}
			},

			dataFromFauneComponent: function(event){
				this.dataFromComponent(event, this.faune_components, 'id_faune_component', 'taxon_faune', 'faune');
			},
			dataFromFloreComponent: function(event){
				this.dataFromComponent(event, this.flore_components, 'id_flore_component', 'taxon_flore', 'flore');
			},
			dataFromEeeFauneComponent: function(event){
				this.dataFromComponent(event, this.eee_faune_components, 'id_eee_faune_component', 'taxon_eee_faune', 'eee_faune');
			},
			dataFromEeeFloreComponent: function(event){
				this.dataFromComponent(event, this.eee_flore_components, 'id_eee_flore_component', 'taxon_eee_flore', 'eee_flore');
			},


			/*
			 * Ferme la fenetre de création
			 */
			closeMareComponent: function(){
				this.$destroy();
				this.$router.push({path: '/carte'});
			},

			/*
			 * Génère les champs pré-remplis en Non renseigné pour la creation
			 */
			generateEmptyField: function(){
				let self = this;

				this.stade_evolution_mare = 1;
				this.largeur_mare = '0';
				this.longueur_mare = '0';

				/*
				 * Sélectionne Non renseigné pour tous les input checkbox (id 1)
				 */
				let checkboxs_vmodel_names = ['usage_mare', 'usage_dechet', 'situation_contexte', 'situation_bati', 'hydrologie_reseau', 'hydrologie_alimentation', 'intervention'];

				checkboxs_vmodel_names.forEach((vmodel_name) => {
					self[vmodel_name].push(1);
				})				

				/*
				 * Sélectionne Non renseigné pour tous les selects (id 1)
				 */
				let selects_models_names = ['situation_cloture', 'caracteristique_forme', 'caracteristique_eau_hauteur', 'caracteristique_fond_mare', 'caracteristique_berge', 'caracteristique_pietinement', 'hydrologie_regime', 'hydrologie_turbidite', 'hydrologie_tampon', 'hydrologie_presence_poisson', 'hydrologie_exutoire', 'ecologie_boisement', 'ecologie_ombrage'];

				selects_models_names.forEach((model_name) => {
					self[model_name] = self[model_name+'s'].find(m => Number(m.id) === Number(1));
				})
			},
			userCanChangeCaracterisation: function(){
				let self = this;

				if(self.$user.role === 'administrateur'){
					self.can_change_caracterisation = true;
				}
				else if(self.$user.role === 'gestionnaire' && self.utilisateur && self.utilisateur.departements){
					self.utilisateur.departements.forEach(function(dep){
						if(dep.code_insee == self.infoNouvelleMare.dep){
        					self.can_change_caracterisation = true;
        				}
					})
				}
			}
		},
		mounted: function(){
			const self = this;
			self.$emit('checkIfIsLoading', true);

            axios.get(base_url + '/mares/create')
            .then(function (resp) {
            	self.type_observateurs = resp.data.type_observateurs;
            	self.type_proprietes = resp.data.type_proprietes;
            	self.type_mares = resp.data.type_mares;
            	self.situation_topographies = resp.data.situation_topographies;
            	self.usage_mares = resp.data.usage_mares;
            	self.usage_dechets = resp.data.usage_dechets;
            	self.situation_contextes = resp.data.situation_contextes;
            	self.situation_batis = resp.data.situation_batis;
            	self.situation_clotures = resp.data.situation_clotures;
            	self.caracteristique_formes = resp.data.caracteristique_formes;
            	self.caracteristique_eau_hauteurs = resp.data.caracteristique_eau_hauteurs;
            	self.stade_evolution_mares = resp.data.stade_evolution_mares;
            	self.caracteristique_fond_mares = resp.data.caracteristique_fond_mares;
				self.caracteristique_berges = resp.data.caracteristique_berges;
				self.caracteristique_pietinements = resp.data.caracteristique_pietinements;
				self.hydrologie_regimes = resp.data.hydrologie_regimes;
				self.hydrologie_reseaux = resp.data.hydrologie_reseaux;
				self.hydrologie_alimentations = resp.data.hydrologie_alimentations;
				self.hydrologie_turbidites = resp.data.hydrologie_turbidites;
				self.hydrologie_tampons = resp.data.hydrologie_tampons;
				self.hydrologie_exutoires = resp.data.hydrologie_exutoires;
				self.ecologie_boisements = resp.data.ecologie_boisements;
				self.ecologie_ombrages = resp.data.ecologie_ombrages;
				self.interventions = resp.data.interventions;
				self.taxon_faunes = resp.data.taxon_faunes;
				self.taxon_flores = resp.data.taxon_flores;
				self.taxon_eee_faunes = resp.data.taxon_eee_faunes;
				self.taxon_eee_flores = resp.data.taxon_eee_flores;
				self.hydrologie_presence_poissons = resp.data.hydrologie_presence_poissons;

            	self.utilisateur = resp.data.utilisateur;
            	self.formulaires = resp.data.formulaires;
            	self.observateurs = resp.data.observateurs;
            	self.caracterisations = resp.data.caracterisations;
            	self.statut_protections = resp.data.statut_protections;

            	self.userCanChangeCaracterisation();

            	self.addEspeceComponent(self.faune_components, 'faune-component', 'id_faune_component', self.id_faune_component++, 'taxon_faune', 'faune');
            	self.addEspeceComponent(self.flore_components, 'flore-component', 'id_flore_component', self.id_flore_component++, 'taxon_flore', 'flore');
            	self.addEspeceComponent(self.eee_faune_components, 'eee-faune-component', 'id_eee_faune_component', self.id_eee_faune_component++, 'taxon_eee_faune', 'eee_faune');
            	self.addEspeceComponent(self.eee_flore_components, 'eee-flore-component', 'id_eee_flore_component', self.id_eee_flore_component++, 'taxon_eee_flore', 'eee_flore');
            	self.generateEmptyField();

            	self.isLoading = false;
            	self.dataLoad = false;      	
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
		},


	}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
/*.multiselect__tags{
	padding: 5px 40px 0px 8px!important;
}*/
.input-group-append {
	margin-top: 1px!important;
}

.multiselect--disabled .multiselect__single{
	background: initial;
}

</style>