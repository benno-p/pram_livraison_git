<template>
	<div class="position-relative" v-if="!isLoading">
        <form method="POST" action="" v-on:submit.prevent="create()">
            <h4 class="text-cen-orange font-weight-bold">Observateur et rédacteur de la fiche</h4>
            <div class="form-group row">
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
                <div class="col-12 col-md-4" v-if="fiche">
                    <label class="col-12 col-form-label text-cen-bleu">Saisie par<span class="text-danger"><b>*</b></span></label>
                    <div class="col-12">
                        <input type="text" class="form-control" readonly="readonly" :value="fiche && fiche.utilisateur_id ? fiche.utilisateur.nom + ' '+ fiche.utilisateur.prenom : ''">
                    </div>
                </div>
            </div>

            <hr>

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
                <div class="col-12 col-md-12">
                    <label for="nom" class="col-12 col-form-label text-cen-bleu">Stade d'évolution de la mare</label>
                </div>
                <div class="col-12 col-md-2 text-center border p-0 mt-1" v-for="stade in stade_evolution_mares">
                    <img v-bind:src="stade.chemin_image" class="img-fluid p-4"/>
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
                        <div class="col-12 p-0" v-if="caracteristique_curage_haut_berge == 1">
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
                            <input type="checkbox" class="custom-control-input" :id="'checkbox_alimentation' + alimentation.id" :value="alimentation.id" v-model="hydrologie_alimentation"  :disabled="disabledNonRenseigneInput('hydrologie_alimentations', 'hydrologie_alimentation', alimentation.id) || disabledIfMareDisparueSelected()"
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
            <vegetation-component v-if="!isLoading && disabledIfMareDisparueSelected() === false"
            :ecologieHelophytesProps="ecologie_helophytes"
            :ecologieHydrophytesProps="ecologie_hydrophytes"
            :ecologieHydrophytesNonEnracinesProps="ecologie_hydrophytes_non_enracines"
            :ecologieAlguesProps="ecologie_algues"
            :ecologieEauLibreProps="ecologie_eau_libre"
            :ecologieFondExondeProps="ecologie_fond_exonde"
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
                            :disabledProps="false"
                            :dataProps="fc"
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
                            :disabledProps="false"
                            :dataProps="fc"
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
                            :disabledProps="false"
                            :dataProps="ef"
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
                            :disabledProps="false"
                            :dataProps="ef"
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
            <div class="form-group row" v-if="fiche && fiche.photos && fiche.photos.length > 0">
                <template v-for="photo in fiche.photos">
                    <div class="col-12 col-md-4">
                        <img :src="photo.chemin + photo.nom" class="img-fluid"/>
                    </div>
                </template>
            </div>
            
            <div class="custom-file" v-else>
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
        </form>

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
		props:[
            // 'fiche',
			'type_mares',
			'stade_evolution_mares',
			'usage_mares',
			'usage_dechets',
			'situation_contextes',
			'situation_batis',
			'situation_clotures',
			'caracteristique_formes',
			'caracteristique_eau_hauteurs',
			'caracteristique_fond_mares',
			'caracteristique_berges',
			'caracteristique_pietinements',
			'hydrologie_regimes',
			'hydrologie_reseaux',
			'hydrologie_alimentations',
			'hydrologie_turbidites',
			'hydrologie_tampons',
			'hydrologie_exutoires',
            'hydrologie_presence_poissons',
			'ecologie_boisements',
			'ecologie_ombrages', 
			'interventions',
			'taxon_faunes',
			'taxon_flores',
			'taxon_eee_faunes',
			'taxon_eee_flores',
            'type_observateur',
            'nom_mare',
            'type_propriete',
            'situation_topographie',
            'type_observateurs',
            'type_proprietes',
            'situation_topographies',
            'utilisateur',
            'code_ofb',
            'ficheProps',
            'observateurs',
            'observateur',
            'mare',
            'caracterisations',
            'statut_protection',
            'statut_protections'
		],
		data(){
			return{
                isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                id_mare: null,

                fiche: this.ficheProps,
				type_mare: [],
				stade_evolution_mare: [],
				usage_mare: [],
				stade_evolution_mare: null,
				pompe_a_nez: 0,
				usage_dechet: [],
				situation_contexte: [],
				situation_bati: [],
				situation_cloture: null,
				haie_contact_mare: 0,
				caracteristique_forme: [],
				caracteristique_eau_hauteur: [],
				caracteristique_fond_mare : [],
				caracteristique_berge: [],
				longueur_mare: '',
				largeur_mare: '',
				caracteristique_pietinement: [],
				caracteristique_curage_haut_berge: 0,
				caracteristique_curage_haut_berge_texte: '',
				hydrologie_regime: [],
				hydrologie_reseau: [],
				hydrologie_alimentation: [],
				hydrologie_turbidite: [],
				hydrologie_tampon: [],
                hydrologie_presence_poisson: [],

				couleur_specifique: 0,
				couleur_specifique_autre: '',
				hydrologie_exutoire: [],
				ecologie_boisement: [],
				ecologie_ombrage: [],
				intervention: [],
				intervenir_objectif: '',

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
                id_fiche: null,

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
                observateur_selected: [],
                before_route: '',

                can_change_caracterisation: false,
                caracterisation_selected: [],

                disabledDates: {
                    from: new Date(moment())
                }

			}
		},
        watch:{
            isLoading: function(val){
                this.$emit('checkIfIsLoading', val);
            }
        },
		methods: {
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
                this.$router.push({path : '/mes_mares/edit/'+this.$route.params.id+'/observateur'});
            },
            addNewObservateur: function(event){
                this.observateurs.push(event);
                this.observateur_selected = this.observateurs.find(o => o.id === event.id);
            },
            handleFileUpload(){
                this.photo = this.$refs.photo.files[0];
                if(this.photo.name){
                    document.getElementById('label_fichier').innerHTML = this.photo.name;
                }
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

                // Usage
                if(!self.type_mare || self.type_mare.length == 0){
                    this.colorInputError(this.$refs, 'type_mare');
                    self.errors.push('Le champ Type de mare est obligatoire');
                }

                if(!self.date_observation || !self.date_observation === ''){
                    this.colorInputError(this.$refs, 'dp_observation');
                    self.errors.push('Le champ Date d\'observation sur le terrain est obligatoire');
                }

                if(self.objectSize(self.observateur_selected) == 0){
                    this.colorInputError(this.$refs, 'observateur_selected');
                    self.errors.push('Le champ Observateur est obligatoire');
                }

                if(self.can_change_caracterisation === true && self.objectSize(self.caracterisation_selected) === 0){
                    this.colorInputError(this.$refs, 'caracterisation_selected');
                    self.errors.push('Le champ Caractérisation est obligatoire');
                }

                // if(!self.stade_evolution_mare){
                //     self.errors.push('Le champ Stade d\'évolution de la mare est obligatoire');
                // }

                // if(!self.usage_mare || self.usage_mare.length == 0){
                //     self.errors.push('Le champ Usage observé de la mare est obligatoire');
                // }

                // if(!self.usage_dechet || self.usage_dechet.length == 0){
                //     self.errors.push('Le champ Présence de déchets est obligatoire');   
                // }

                // Caractéristique
                // if(!self.caracteristique_forme || self.caracteristique_forme.length == 0){
                //     self.errors.push('Le champ Forme est obligatoire');
                // }

                // if(!self.caracteristique_eau_hauteur || self.caracteristique_eau_hauteur.length == 0){
                //     self.errors.push('Le champ Hauteur d\'eau maximum est obligatoire');
                // }

                // if(!self.longueur_mare){
                //     self.errors.push('Le champ Longueur moyenne est obligatoire');
                // }

                // if(!self.largeur_mare){
                //     self.errors.push('Le champ Largeur moyenne est obligatoire');
                // }

                // if(!self.caracteristique_fond_mare || self.caracteristique_fond_mare.length == 0){
                //     self.errors.push('Le champ Nature du fond de la mare est obligatoire');
                // }

                // if(!self.caracteristique_berge || self.caracteristique_berge.length == 0){
                //     self.errors.push('Le champ Berges en pente douce est obligatoire');
                // }

                // if(self.caracteristique_curage_haut_berge == 1 && self.caracteristique_curage_haut_berge_texte == ''){
                //     self.errors.push('Le champ bourrelet de curage en haut de berge pourcentage est obligatoire');
                // }

                // if(!self.caracteristique_pietinement || self.caracteristique_pietinement.length == 0){
                //     self.errors.push('Le champ Surpiétinement des abords est obligatoire');
                // }

                // Hydrologie
                // if(!self.hydrologie_regime || self.hydrologie_regime.length == 0){
                //     self.errors.push('Le champ Régime hydrologique est obligatoire');
                // }

                // if(!self.hydrologie_turbidite || self.hydrologie_turbidite.length == 0){
                //     self.errors.push('Le champ Turbidité de l\'eau est obligatoire');
                // }

                // if(self.couleur_specifique == 1 && !self.couleur_specifique_autre){
                //     self.errors.push('Merci de préciser la couleur de l\'eau'); 
                // }

                // if(!self.hydrologie_exutoire || self.hydrologie_exutoire.length == 0){
                //     self.errors.push('Le champ Exutoire est obligatoire');
                // }

                //Ecologie
                // if(!self.ecologie_boisement || self.ecologie_boisement.length == 0){
                //     self.errors.push('Le champ Boisement / embroussaillement des abords est obligatoire');  
                // }

                // if(!self.ecologie_ombrage || self.ecologie_ombrage.length == 0){
                //     self.errors.push('Le champ Ombrage surface par ligneux est obligatoire');
                // }

                // if(self.total_vegetation && self.total_vegetation != '' && Number(self.total_vegetation) !=  Number(100)){
                //     self.errors.push('Le total du recouvrement de la végétation doit être égale à 100%');
                // }

                     
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
                formData.append('type_observateur_id', self.type_observateur && self.type_observateur.id ? self.type_observateur.id : '');
                formData.append('type_propriete_id', self.type_propriete && self.type_propriete.id ? self.type_propriete.id : '');
                formData.append('situation_topographie_id', self.situation_topographie && self.situation_topographie.id ? self.situation_topographie.id : '');
                formData.append('code_ofb', self.code_ofb ? self.code_ofb : '');
                formData.append('statut_protection_id', self.statut_protection && self.statut_protection.id ? self.statut_protection.id : '');
                /*
                 * Mare fiche
                 */
                 // Usage
                formData.append('observateur_id', self.observateur_selected && self.observateur_selected.id ? self.observateur_selected.id : '');
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
                formData.append('fiche_id', self.id_fiche ? self.id_fiche : '');

                formData.append('photo', this.photo ? this.photo : '');

                let url = '';
                if(self.$route.name == 'mes_mares.edit'){
                    url = base_url + '/mes_mares/' + self.mare.id;
                }else if(self.$route.name === 'mes_mares.fiche_edit'){
                    url = base_url + '/fiches_attentes/update/' + self.fiche.id;
                }else{
                    url = base_url + '/mares/' + self.id_mare;
                }


                formData.append('_method', 'PUT');
                axios.post(url, formData, { 
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
                        self.showSuccess([response.data.message]);

                        if(self.$route.name === 'mes_mares.edit' || self.$route.name === 'mes_mares.nouvelle_fiche'){
                            self.$router.push({path: '/mes_mares'});

                        }else if(self.$route.name === 'mes_mares.fiche_edit' || self.$route.name === 'mes_fiches.nouvelle_fiche'){
                            self.$router.push({path: '/fiches_attentes'});

                        }else if(self.$route.name == 'recherche.nouvelle_fiche'){
                            self.$router.push({path: '/recherche'});

                        }else{
                            self.$router.push({path: '/carte'});  
                        }

                        self.isLoading = false;
                        return self.$emit('checkIfIsLoading', false);              
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

			closeMareComponent: function(){
				this.$destroy();

                if(this.$route.name === 'mes_mares.nouvelle_fiche' || this.$route.name === 'mes_mares.edit'){
                    this.$router.push({path: '/mes_mares'});

                }else if(this.$route.name == 'recherche.nouvelle_fiche'){
                    this.$router.push({path: '/recherche'});

                }else if(this.$route.name === 'mes_mares.fiche_edit' || this.$route.name === 'mes_fiches.nouvelle_fiche'){
                    this.$router.push({path: '/fiches_attentes'});

                }else{ 
                    this.$router.push({path: '/carte'});   
                }
			},

            loadData: function(){
                let self = this;

                //Usage
                this.type_mare = this.type_mares.find(t => t.id == this.fiche.type_mare_id);
                this.stade_evolution_mare = this.fiche.stade_evolution_mare_id;

                this.fiche.usage_mares.forEach(function(usage_mare){
                    self.usage_mare.push(usage_mare.id);
                });
                this.pompe_a_nez = this.fiche.pompe_a_nez == true ? 1 : 0;
                this.fiche.usage_dechets.forEach(function(usage_dechet){
                    self.usage_dechet.push(usage_dechet.id);
                });


                //Situation
                this.fiche.situation_contextes.forEach(function(situation_contexte){
                    self.situation_contexte.push(situation_contexte.id);
                });
                this.fiche.situation_batis.forEach(function(situation_bati){
                    self.situation_bati.push(situation_bati.id);
                });
                this.situation_cloture = this.situation_clotures.find(sc => sc.id == this.fiche.situation_cloture_id);
                this.haie_contact_mare = this.fiche.haie_contact_mare == true ? 1 : 0;

                //Caractéristique
                this.caracteristique_forme = this.caracteristique_formes.find(cf => cf.id == this.fiche.caracteristique_forme_id);
                this.caracteristique_eau_hauteur = this.caracteristique_eau_hauteurs.find(ceh => ceh.id == this.fiche.caracteristique_eau_hauteur_id);
                this.longueur_mare = this.fiche.caracteristique_longueur;
                this.largeur_mare = this.fiche.caracteristique_largeur;
                this.caracteristique_fond_mare = this.caracteristique_fond_mares.find(cfm => cfm.id == this.fiche.caracteristique_fond_mare_id);
                this.caracteristique_berge = this.caracteristique_berges.find(cb => cb.id == this.fiche.caracteristique_berge_id);
                this.caracteristique_pietinement = this.caracteristique_pietinements.find(cp => cp.id == this.fiche.caracteristique_pietinement_id);
                this.caracteristique_curage_haut_berge = this.fiche.caracteristique_curage_haut_berge == true ? 1 : 0;
                this.caracteristique_curage_haut_berge_texte = this.fiche.caracteristique_curage_haut_berge_texte;

                //Hydrologie
                this.hydrologie_regime = this.hydrologie_regimes.find(hr => hr.id == this.fiche.hydrologie_regime_id);
                this.fiche.hydrologie_reseaus.forEach(function(hydrologie_reseau){
                    self.hydrologie_reseau.push(hydrologie_reseau.id);
                });
                this.fiche.hydrologie_alimentations.forEach(function(hydrologie_alimentation){
                    self.hydrologie_alimentation.push(hydrologie_alimentation.id);
                });
                this.hydrologie_turbidite = this.hydrologie_turbidites.find(ht => ht.id == this.fiche.hydrologie_turbidite_id);
                this.couleur_specifique = this.fiche.couleur_specifique == true ? 1 : 0;
                this.couleur_specifique_autre = this.fiche.couleur_specifique_autre;
                this.hydrologie_tampon = this.hydrologie_tampons.find(ht => ht.id == this.fiche.hydrologie_tampon_id);
                this.hydrologie_exutoire = this.hydrologie_exutoires.find(he => he.id == this.fiche.hydrologie_exutoire_id);
                this.hydrologie_presence_poisson = this.hydrologie_presence_poissons.find(pp => pp.id == this.fiche.hydrologie_presence_poisson_id);

                //Ecologie
                this.ecologie_boisement = this.ecologie_boisements.find(eb => eb.id == this.fiche.ecologie_boisement_id);
                this.ecologie_ombrage = this.ecologie_ombrages.find(eo => eo.id == this.fiche.ecologie_ombrage_id);
                this.ecologie_helophytes = this.fiche.ecologie_helophytes;
                this.ecologie_hydrophytes = this.fiche.ecologie_hydrophytes;
                this.ecologie_hydrophytes_non_enracines = this.fiche.ecologie_hydrophytes_non_enracines;
                this.ecologie_algues = this.fiche.ecologie_algues;
                this.ecologie_eau_libre = this.fiche.ecologie_eau_libre;
                this.ecologie_fond_exonde = this.fiche.ecologie_fond_exonde;


                //Intervenir
                this.fiche.interventions.forEach(function(intervention){
                    self.intervention.push(intervention.id);
                });
                this.intervenir_objectif = this.fiche.intervenir_objectif;
                this.remarque_generale = this.fiche.remarque_generale;
                this.date_observation = this.fiche.date_observation;


                if(this.fiche.faunes && this.fiche.faunes.length > 0){
                    this.fiche.faunes.forEach(function(faune){
                        // let taxon = self.taxon_faunes.find(tf => tf.id == faune.taxon_faune_id);
                        let taxon = self.taxon_faunes.find(tf => tf.id == faune.tax_faune_groupe_id);
                        let espece = taxon.faunes.find(f => f.id == faune.id);

                        self.faune_components.push({
                            component: self.faune_component,
                            id_faune_component: self.id_faune_component++,
                            taxon_faune : taxon,
                            faune: espece,
                            nombre: faune.pivot.nombre_observe,
                        });
                    })
                }

                if(this.fiche.flores && this.fiche.flores.length > 0){
                    this.fiche.flores.forEach(function(flore){
                        // let taxon = self.taxon_flores.find(tf => tf.id == flore.taxon_flore_id);
                        let taxon = self.taxon_flores.find(tf => tf.id == flore.tax_flore_groupe_id);
                        let espece = taxon.flores.find(f => f.id == flore.id);

                        self.flore_components.push({
                            component: self.flore_component,
                            id_flore_component: self.id_flore_component++,
                            taxon_flore : taxon,
                            flore: espece,
                        });
                    })
                }

                if(this.fiche.eee_faunes && this.fiche.eee_faunes.length > 0){
                    this.fiche.eee_faunes.forEach(function(eee_faune){
                        // let taxon = self.taxon_eee_faunes.find(tf => tf.id == eee_faune.taxon_eee_faune_id);
                        let taxon = self.taxon_eee_faunes.find(tf => tf.id == eee_faune.tax_eee_faune_groupe_id);
                        let espece = taxon.eee_faunes.find(f => f.id == eee_faune.id);

                        self.eee_faune_components.push({
                            component: self.eee_faune_component,
                            id_eee_faune_component: self.id_eee_faune_component++,
                            taxon_eee_faune : taxon,
                            eee_faune: espece,
                            nombre: eee_faune.pivot.nombre_observe,
                        });
                    })
                }

                if(this.fiche.eee_flores && this.fiche.eee_flores.length > 0){
                    this.fiche.eee_flores.forEach(function(eee_flore){
                        // let taxon = self.taxon_eee_flores.find(tf => tf.id == eee_flore.taxon_eee_flore_id);
                        let taxon = self.taxon_eee_flores.find(tf => tf.id == eee_flore.tax_eee_flore_groupe_id);
                        let espece = taxon.eee_flores.find(f => f.id == eee_flore.id);

                        self.eee_flore_components.push({
                            component: self.eee_flore_component,
                            id_eee_flore_component: self.id_eee_flore_component++,
                            taxon_eee_flore : taxon,
                            eee_flore: espece,
                            pourcentage: eee_flore.pivot.pourcentage,
                        });
                    })
                }

                if(this.fiche.observateur_id){
                    this.observateur_selected = this.observateurs.find(o => o.id ===this.fiche.observateur_id);
                }

                this.isLoading = false;
            },
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
                        if(dep.code_insee == self.mare.departement_code){
                            self.can_change_caracterisation = true;
                        }
                    })
                }
                // else if(self.mare && self.utilisateur.groupe && self.$user.role === 'gestionnaire'){
                //     if(self.utilisateur.groupe.departements){                       
                //         self.utilisateur.groupe.departements.forEach(function(dep){
                //             if(dep.code_insee == self.mare.departement_code){
                //                 self.can_change_caracterisation = true;
                //             }
                //         });
                //     }
                //     if(self.utilisateur.groupe.intercommunalites){
                //         self.utilisateur.groupe.intercommunalites.forEach(function(interco){
                //             if(interco.siren == self.mare.intercommunalite_siren){
                //                 self.can_change_caracterisation = true;
                //             }
                //         })
                //     }
                //     if(self.utilisateur.groupe.communes){
                //         self.utilisateur.groupe.communes.forEach(function(com){
                //             if(com.insee == self.mare.commune_insee){
                //                 self.can_change_caracterisation = true;
                //             }
                //         });
                //     }
                // }
            }
		},
		mounted(){
            const self = this;

            if(this.$route.name == 'mes_mares.edit'){
                axios.get(base_url + '/mes_mares/'+this.$route.params.id+'/edit')
                .then(function (resp) {
                    // self.mare = resp.data.mare;
                    self.fiche = resp.data.fiche;
                    self.id_mare = self.$route.params.id;
                    self.id_fiche = self.fiche.id;
                    // self.utilisateur = resp.data.utilisateur;

                    self.loadData();

                    self.isLoading = false;

                    let observateur_id = self.observateur && self.observateur.id ? self.observateur.id : null;

                    if((self.mare.utilisateur_id != self.utilisateur.id) && (self.mare.observateur_id != observateur_id )){
                        return self.$router.push({path: '/errors/404'});
                    }

                })
                .catch(function (resp) {
                    return self.checkError(resp);
                });
            }else if(this.$route.name === 'mes_mares.fiche_edit'){
                if(Number(self.fiche.utilisateur_id) != Number(self.utilisateur.id)){
                    return self.$router.push({path: '/errors/404'});
                }
                self.loadData();
            }else{
                this.userCanChangeCaracterisation();
                this.id_mare = this.$route.params.id;
                this.addEspeceComponent(this.faune_components, 'faune-component', 'id_faune_component', this.id_faune_component++, 'taxon_faune', 'faune');
                this.addEspeceComponent(this.flore_components, 'flore-component', 'id_flore_component', this.id_flore_component++, 'taxon_flore', 'flore');
                this.addEspeceComponent(this.eee_faune_components, 'eee-faune-component', 'id_eee_faune_component', this.id_eee_faune_component++, 'taxon_eee_faune', 'eee_faune');
                this.addEspeceComponent(this.eee_flore_components, 'eee-flore-component', 'id_eee_flore_component', this.id_eee_flore_component++, 'taxon_eee_flore', 'eee_flore');
                self.generateEmptyField();
                this.isLoading = false;
            } 
        },
	}


</script>

<style>
.multiselect--disabled{
	opacity: 1!important;
}

</style>