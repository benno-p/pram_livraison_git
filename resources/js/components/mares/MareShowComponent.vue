<template>
	<div class="position-relative">
        <div class="form-control-group row mb-4" v-if="fiche.photos && fiche.photos.length > 0">
            <div class="col-12 col-md-8">
                <div class="d-flex flex-wrap">
                    <div class="col-12 col-md-6">
                        <label for="nom" class="col-12 col-form-label text-cen-bleu">Observée par<span class="text-danger"><b>*</b></span></label>
                        <div class="col-12">
                            <input type="text" class="form-control" :value="fiche.observateur_id ? fiche.observateur.nom + ' ' + fiche.observateur.prenom : ''" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="nom" class="col-12 col-form-label text-cen-bleu">Saisie par<span class="text-danger"><b>*</b></span></label>
                        <div class="col-12">
                            <input type="text" class="form-control" :value="fiche.utilisateur_id ? fiche.utilisateur.nom + ' ' + fiche.utilisateur.prenom : ''" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="nom" class="col-12 col-form-label text-cen-bleu">Date de saisie<span class="text-danger"><b>*</b></span></label>
                        <div class="col-12">
                            <input type="text" class="form-control" :value="convertDate(fiche.created_at)" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="date_observation" class="col-12 col-form-label">Date d'observation sur le terrain</label>
                        <div class="col-12">
                            <input type="text" class="form-control" :value="convertDate(date_observation)" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="col-12">
                            <button class="btn btn-success btn-sm" @click="exportFiche(fiche.id)">
                                <i class="fas fa-file-pdf" v-if="!export_loading"></i>
                                <i class="spinner-border spinner-border-sm" v-else></i>
                                Exporter la fiche
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mt-4">
                <template v-for="photo in fiche.photos">
                    <enlargeable-image :src="photo.chemin + photo.nom" :src_large="photo.chemin + photo.nom" />
                </template>
            </div>
        </div>
        
        <div v-else class="form-control-group row mb-4">
            <div class="col-12 col-md-3">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Observée par<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.observateur_id ? fiche.observateur.nom + ' ' + fiche.observateur.prenom : ''" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Saisie par<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.utilisateur_id ? fiche.utilisateur.nom + ' ' + fiche.utilisateur.prenom : ''" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Date de saisie<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="convertDate(fiche.created_at)" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <label for="date_observation" class="col-12 col-form-label">Date d'observation sur le terrain</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="convertDate(date_observation)" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <label for="date_observation" class="col-12 col-form-label">&nbsp;</label>
                <div class="col-12">
                    <button class="btn btn-success btn-sm" @click="exportFiche(fiche.id)">
                        <i class="fas fa-file-pdf" v-if="!export_loading"></i>
                        <i class="spinner-border spinner-border-sm" v-else></i>
                        Exporter la fiche
                    </button>
                </div>
            </div>
        </div>
		<h4 class="text-cen-orange font-weight-bold">Usages</h4>
        <div class="form-group row">
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Type de mares<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="type_mare && type_mare.nom ? type_mare.nom : '' " readonly="readonly">
                </div>
            </div>


            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Stade d'évolution de la mare<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="checkStadeEvolutionMare()" readonly="readonly">
                </div>
            </div>

            <div class="col-12 col-md-4" v-if="fiche.caracterisation_id">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Caracterisation<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracterisation_selected && caracterisation_selected.nom_interne ? caracterisation_selected.nom : ''" readonly="readonly">
                </div>
            </div>
        </div>


        <div class="form-group row">
        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Usage(s) observé(s) de la mare<span class="text-danger"><b>*</b></span></label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="usage in fiche.usage_mares" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_usage' + usage.id+ '_' + fiche.id" :value="usage.id" v-model="usage_mare" disabled="disabled"
                        >
                        <label class="custom-control-label" :for="'checkbox_usage' + usage.id+ '_' + fiche.id">{{usage.nom}}</label>
                    </div>
                </div>
            </div>

        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Présence de déchets<span class="text-danger"><b>*</b></span></label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="dechet in fiche.usage_dechets" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_dechet' + dechet.id+ '_' + fiche.id" :value="dechet.id" v-model="usage_dechet" disabled="disabled" 
                        >
                        <label class="custom-control-label" :for="'checkbox_dechet' + dechet.id+ '_' + fiche.id">{{dechet.nom}}</label>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-1">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Pompe à nez</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.pompe_a_nez == 1 ? 'Oui' : 'Non'" readonly="readonly">
                </div>
            </div>

        </div>

        <hr>

        <h4 class="text-cen-orange font-weight-bold">Situation</h4>
        <div class="form-group row">
        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Contexte</label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="contexte in fiche.situation_contextes" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_contexte' + contexte.id+ '_' + fiche.id" :value="contexte.id" v-model="situation_contexte" disabled="disabled"
                        >
                        <label class="custom-control-label" :for="'checkbox_contexte' + contexte.id+ '_' + fiche.id">{{contexte.nom}}</label>
                    </div>
                </div>
            </div>

        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Petit patrimoine bâti associé</label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="bati in fiche.situation_batis" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_bati' + bati.id+ '_' + fiche.id" :value="bati.id" v-model="situation_bati" disabled="disabled"
                        >
                        <label class="custom-control-label" :for="'checkbox_bati' + bati.id+ '_' + fiche.id">{{bati.nom}}</label>
                    </div>
                </div>
            </div>
        
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Mare cloturée</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="situation_cloture && situation_cloture.nom ? situation_cloture.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Présence d'une haie en contact avec la mare</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.haie_contact_mare == 1 ? 'Oui' : 'Non'" readonly="readonly">
                </div>
            </div>
        </div>

        <hr>

        <h4 class="text-cen-orange font-weight-bold">Caractéristiques abiotiques de la mare</h4>
        <div class="form-group row">
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Forme<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracteristique_forme && caracteristique_forme.nom ? caracteristique_forme.nom : '' " readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Hauteur d'eau maximum observée aujourd'hui<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracteristique_eau_hauteur && caracteristique_eau_hauteur.nom ? caracteristique_eau_hauteur.nom : '' " readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Nature du fond de la mare<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracteristique_fond_mare && caracteristique_fond_mare.nom ? caracteristique_fond_mare.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label class="col-12 col-form-label text-cen-bleu">Longueur de la mare (moyenne)<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="number" step="0.01" class="form-control" :value="longueur_mare" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label class="col-12 col-form-label text-cen-bleu">Largeur de la mare (moyenne)<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="number" step="0.01" class="form-control" :value="largeur_mare" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Berges en pente douce<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracteristique_berge && caracteristique_berge.nom ? caracteristique_berge.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Surpiétinement des abords<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="caracteristique_pietinement && caracteristique_pietinement.nom ? caracteristique_pietinement.nom : '' " readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Bourrelet de curage en haut de berge<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.caracteristique_curage_haut_berge == 1 ? 'Oui' : 'Non'" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4" v-if="fiche.caracteristique_curage_haut_berge_texte">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Pourcentage du périmètre de la mare<span class="text-danger"><b>*</b></span></label>
                <div class="col-12 input-group">
                    <input type="text" class="form-control" :value="fiche.caracteristique_curage_haut_berge_texte" readonly="readonly">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
        </div>

<!-- <div class="input-group mb-3">
<input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
<div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">@example.com</span>
</div>
</div> -->

   
        <hr>

        <h4 class="text-cen-orange font-weight-bold">Hydrologie</h4>
        <div class="form-group row">
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Régime hydrologique<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="hydrologie_regime && hydrologie_regime.nom ? hydrologie_regime.nom : '' " readonly="readonly">
                </div>
            </div>
        
        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Liaison(s) avec le réseau hydrographique superficiel</label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="reseau in fiche.hydrologie_reseaus" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_reseau' + reseau.id + '_' + fiche.id" :value="reseau.id" v-model="hydrologie_reseau" disabled="disabled"
                        >
                        <label class="custom-control-label" :for="'checkbox_reseau' + reseau.id + '_' + fiche.id">{{reseau.nom}}</label>
                    </div>
                </div>
            </div>

        	<div class="col-12 col-md-4">
        		<label for="nom" class="col-12 col-form-label text-cen-bleu">Alimentation spécifique</label>
                <div class="d-flex flex-wrap col-12">
                    <div v-for="alimentation in fiche.hydrologie_alimentations" class="custom-control custom-checkbox mb-2 col-12 col-md-6">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_alimentation' + alimentation.id + '_' + fiche.id" :value="alimentation.id" v-model="hydrologie_alimentation" disabled="disabled" 
                        >
                        <label class="custom-control-label" :for="'checkbox_alimentation' + alimentation.id + '_' + fiche.id">{{alimentation.nom}}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Turbidité de l'eau<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="hydrologie_turbidite && hydrologie_turbidite.nom ? hydrologie_turbidite.nom : '' " readonly="readonly">
                </div>
            </div>
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Zone tampon</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="hydrologie_tampon && hydrologie_tampon.nom ? hydrologie_tampon.nom : '' " readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Exutoire<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="hydrologie_exutoire && hydrologie_exutoire.nom ? hydrologie_exutoire.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">L'eau a une couleur spécifique<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="fiche.couleur_specifique == 1 ? 'Oui' : 'Non'" readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4 p-0" v-if="couleur_specifique == 1">
                <label class="col-12 col-form-label text-cen-bleu">Si oui, précisez<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="couleur_specifique_autre" readonly="readonly">
                </div>
            </div>
        </div>

        <hr>

        <h4 class="text-cen-orange font-weight-bold">Écologie</h4>
        <vegetation-component v-if="!isLoading"
        :ecologieHelophytesProps="ecologie_helophytes"
        :ecologieHydrophytesProps="ecologie_hydrophytes"
        :ecologieHydrophytesNonEnracinesProps="ecologie_hydrophytes_non_enracines"
        :ecologieAlguesProps="ecologie_algues"
        :ecologieEauLibreProps="ecologie_eau_libre"
        :ecologieFondExondeProps="ecologie_fond_exonde"
        :disabledProps="true"
        v-on:dataFromVegetationComponent="dataFromVegetationComponent"
        ></vegetation-component>

        <div class="form-group row">
        	<div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Boisement / embroussaillement des abords<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="ecologie_boisement && ecologie_boisement.nom ? ecologie_boisement.nom : '' " readonly="readonly">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Ombrage surface par ligneux (soleil au zénith)<span class="text-danger"><b>*</b></span></label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="ecologie_ombrage && ecologie_ombrage.nom ? ecologie_ombrage.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12 col-md-6">
                <label for="nom" class="col-12 col-form-label text-cen-bleu">Présence de poisson</label>
                <div class="col-12">
                    <input type="text" class="form-control" :value="hydrologie_presence_poisson && hydrologie_presence_poisson.nom ? hydrologie_presence_poisson.nom : '' " readonly="readonly">
                </div>
            </div>
        </div>
        
    	<div class="card ml-3 mr-3">
    		<div class="card-header">
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
					:dataProps="fc"
					:disabledProps="true"
					>
					</faune-component>
				</template>
            </div>
        </div>

        <div class="card ml-3 mr-3 mt-4">
    		<div class="card-header">
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
					:dataProps="fc"
					:disabledProps="true"
					>
					</flore-component>
				</template>
            </div>
        </div>

        <div class="card ml-3 mr-3 mt-4">
    		<div class="card-header">
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
					:dataProps="ef"
					:disabledProps="true"
					>
					</eee-faune-component>
				</template>
            </div>
        </div>
        <div class="card ml-3 mr-3 mt-4">
    		<div class="card-header">
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
					:dataProps="ef"
					:disabledProps="true"
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
                    <div v-for="travaux in fiche.interventions" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
                        <input type="checkbox" class="custom-control-input" :id="'checkbox_travaux' + travaux.id + '_' + fiche.id" :value="travaux.id" v-model="intervention" disabled="disabled" 
                        >
                        <label class="custom-control-label" :for="'checkbox_travaux' + travaux.id + '_' + fiche.id">{{travaux.nom}}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
        	<div class="col-12 col-md-6">
                <label class="col-12 col-form-label text-cen-bleu">Dans quel(s) Objectif(s)</label>
                <div class="col-12">
                    <textarea class="form-control w-100" rows="5" v-model="intervenir_objectif" disabled="disabled"></textarea>
                </div>
            </div>
        </div>

        <hr>

        <div class="form-group row">
            <div class="col-12 col-md-6">
                <label class="col-12 col-form-label text-cen-bleu">Remarques générales</label>
                <div class="col-12">
                    <textarea class="form-control w-100" rows="5" v-model="remarque_generale" readonly="readonly"></textarea>
                </div>
            </div>
        </div>

        <hr>


        <div class="form-group row mb-0">
            <div class="col-12">
<!--                 <button type="submit" class="btn btn-success">
                    Enregistrer
                </button> -->
                <button class="btn btn-secondary" aria-label="Close" @click="closeMareComponent()">
    				Quitter
    			</button>
            </div>
        </div>
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
			'fiche',
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
            'caracterisations'
		],
		data(){
			return{
                isLoading: true,
                export_loading: false,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                base_url: base_url,

				type_mare: this.type_mares.find(tm => tm.id == this.fiche.type_mare_id),
				stade_evolution_mare: this.stade_evolution_mares.find(sem => sem.id == this.fiche.stade_evolution_mare_id),
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

                remarque_generale: '',
                date_observation: '',

                caracterisation_selected: [],

                disabledDates: {
                    from: new Date(moment())
                }

			}
		},
		methods: {
            exportFiche(id){
                let self = this;
                self.export_loading = true;
                axios.get(self.base_url + '/exports/fiche_pdf/'+id)
                .then(function (resp) {
                    window.location.href = resp.data.url;
                    self.export_loading = false;
                })
                .catch(function (resp) {
                    return self.checkError(resp);
                });
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
            checkStadeEvolutionMare: function(){
                let result = '';

                if(this.fiche.stade_evolution_mare_id){
                    let stade = this.stade_evolution_mares.find(s => s.id == this.fiche.stade_evolution_mare_id);
                    if(stade){
                        result = stade.nom;
                        if(stade.descriptif){
                            result += ' : ' + stade.descriptif;
                        }  
                    }
                }

                return result;
            },
			closeMareComponent: function(){
				this.$destroy();

                if(this.$route.name === 'mes_mares.consultation'){
                    this.$router.push({path: '/mes_mares'});

                }else if(this.$route.name === 'validation_mares.fiches'){
                    this.$router.push({path: '/validation_mares'});

                }else if(this.$route.name === 'validation_fiches.fiches'){
                    this.$router.push({path: '/validation_fiches'});

                }else if(this.$route.name == 'recherche.consultation'){
                    this.$router.push({path: '/recherche'});

                }else{
                    this.$router.push({path: '/carte'});
                }

			},
		},
		mounted(){

			let self = this;
            console.log(this.hydrologie_presence_poissons)
            console.log(this.hydrologie_regimes)

			//Usage
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
            // console.log(this.hydrologie_presence_poissons)
            // console.log(this.fiche.hydrologie_presence_poisson_id)
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

            this.remarque_generale = this.fiche.remarque_generale;
            this.date_observation = this.fiche.date_observation != null ? this.fiche.date_observation : '';

            if(this.fiche.caracterisation_id){
                this.caracterisation_selected = this.caracterisations.find(c => c.id === this.fiche.caracterisation_id);
            }

            self.isLoading = false;
		}
	}


</script>

<style>
.multiselect--disabled{
	opacity: 1!important;
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before{
    background-color: rgba(0, 123, 255, 1)!important;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label{
    color: black!important;
}
.form-check-input[disabled] ~ .form-check-label, .form-check-input:disabled ~ .form-check-label{
    color: black!important;
}
</style>