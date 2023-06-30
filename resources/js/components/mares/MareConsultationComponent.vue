<template>
	<div class="position-relative">
		<div class="w-100 pt-4 pb-4" id="mareComponent" v-bind:style="{ height: computedHeight }">
			<div class="vld-parent">
	            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
	        </div>
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
			                	<div class="card">
			                		<div class="card-header bg-dark text-light">
			                			<div class="float-right">
		                    				Identifiant de la mare : <b>{{mare.id}}</b>
		                    			</div>
		                    			<h4 class="font-weight-bold">Données générales</h4>
		                    		</div>
		                    		<div class="card-body row">
				                    	<div class="form-group row col-8">
				                    		<div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Observée par<span class="text-danger"><b>*</b></span></label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" readonly="readonly" :value="mare.observateur_id ? mare.observateur.nom + ' '+ mare.observateur.prenom : ''">
					                            </div>
					                        </div>
				                        	<div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Saisie par<span class="text-danger"><b>*</b></span></label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" readonly="readonly" :value="mare.utilisateur_id ? mare.utilisateur.nom + ' '+ mare.utilisateur.prenom : ''">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Date de saisie</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="convertDate(mare.created_at)" readonly="readonly">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4" v-if="checkIfUserIsRedacteurOrObservateur()">
					                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Observateur - Je suis le<span class="text-danger"><b>*</b></span> :</label>
		                                        <div class="col-12">
		                                            <multiselect 
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
					                        <div class="col-12 col-md-4" v-else>
					                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Observateur - profil<span class="text-danger"><b>*</b></span> :</label>
		                                        <div class="col-12">
		                                        	<input class="form-control" type="text" readonly="readonly" :value="type_observateur && type_observateur.nom ? type_observateur.nom : ''">
		                                        </div>
					                        </div>
				                        <!-- </div> -->

				                        <!-- <div class="form-group row"> -->
				                        	<div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Source de la donnée</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" v-model="nom_mare" v-if="checkIfUserIsRedacteurOrObservateur()">
					                                <input v-else type="text" class="form-control" :value="nom_mare" readonly="readonly">
					                            </div>
					                        </div>

				                        	<div class="col-12 col-md-4">
					                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Type de propriété<span class="text-danger"><b>*</b></span></label>
		                                        <div class="col-12" v-if="checkIfUserIsRedacteurOrObservateur()">
		                                            <multiselect 
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
		                                        <div class="col-12"  v-else>
		                                        	<input type="text" class="form-control" :value="type_propriete && type_propriete.nom ? type_propriete.nom : ''" readonly="readonly">
		                                        </div>
					                        </div>
					                        <div class="col-12 col-md-4">
					                            <label for="nom" class="col-12 col-form-label text-cen-bleu">Topographie</label>
		                                        <div class="col-12" v-if="checkIfUserIsRedacteurOrObservateur()">
		                                            <multiselect 
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
		                                        <div class="col-12"  v-else>
		                                        	<input type="text" class="form-control" :value="situation_topographie && situation_topographie.nom ? situation_topographie.nom : ''" readonly="readonly">
		                                        </div>
					                        </div>
				                        <!-- </div> -->

				                        <!-- <div class="form-group row"> -->
					                        <div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">X Lambert93</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="mare.x_l93" readonly="readonly">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Y Lambert93</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="mare.y_l93" readonly="readonly">
					                            </div>
					                        </div>
				                        <!-- </div> -->

				                        <!-- <div class="form-group row"> -->
				                        	<div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Departement</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="mare.departement_code" readonly="readonly">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Intercommunalité</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="mare.intercommunalite" readonly="readonly">
					                            </div>
					                        </div>
				                        	<div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Commune</label>
					                            <div class="col-12">
					                                <input type="text" class="form-control" :value="mare.commune" readonly="readonly">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4">
					                            <label class="col-12 col-form-label text-cen-bleu">Code OFB</label>
					                            <div class="col-12">

													<div class="input-group" v-if="checkIfUserCanChangeInput('code_ofb')">
						                                <input type="text" :class="'form-control '+code_ofb_is_save" v-model="code_ofb">
						                                <div class="input-group-append mt-0" style="margin-top: 0!important;">
															<button class="btn btn-outline-secondary" @click="saveCodeOfb()">
																<i class="fas fa-save"></i>
															</button>
														</div>
						                            </div>

					                                <input v-else type="text" class="form-control" disabled="disabled" readonly="readonly" :value="code_ofb">
					                            </div>
					                        </div>
					                        <div class="col-12 col-md-4" v-if="checkIfUserCanChangeInput('statut_protection')">
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
				                        <div class="col-4">
				                        	<mini-carte-component 
				                        	v-if="objectSize(mare) > 0"
				                        	:mare="mare"
				                        	:utilisateur="utilisateur"
				                        	:observateur="observateur"
				                        	v-on:newInfoMareAfterDrag="newInfoMareAfterDrag"
				                        	></mini-carte-component>
				                        </div>
				                    </div>
				                </div>

		                        <div class="card mt-4" v-if="$user.authenticated === true">
		                        	<div class="card-header bg-dark text-light">
				                        <div class="d-flex justify-content-between flex-wrap" v-if="$route.name === 'mare.consultation' || $route.name === 'mes_mares.consultation' || $route.name === 'mes_fiches.consultation' || $route.name === 'recherche.consultation'">
				                        	<h4 class="font-weight-bold">Fiches</h4>
					                        <button class="btn btn-success btn-sm" v-if="$user.authenticated == true && $user.role != 'visiteur' && mare.statut && mare.statut.nom_interne && mare.statut.nom_interne === 'valider'" @click.prevent="createNouvelleFiche()">
								            	<i class="fas fa-plus-circle"></i> Créer une nouvelle fiche
								            </button>
								        </div>

								        <div class="d-flex justify-content-between flex-wrap" v-else-if="$route.name === 'mare.nouvelle_fiche' || $route.name === 'mes_mares.nouvelle_fiche' || $route.name === 'mes_fiches.nouvelle_fiche'">
								        	<h4 class="font-weight-bold">Nouvelle Fiche</h4>
								            <button class="btn btn-danger btn-sm" @click.prevent="annulerNouvelleFiche()">
								            	<i class="fas fa-undo"></i> Annuler
								            </button>
								        </div>

								        <div class="d-flex justify-content-between flex-wrap" v-else>
								        	<h4 class="font-weight-bold">Fiches</h4>
								        </div>
								    </div>
								    <div class="card-body">
							            <router-view v-if="!isLoading"
											name="third"
											:mare="mare"
											:ficheProps="fiche"
											:nom_mare="nom_mare"
											:type_observateur="type_observateur"
											:type_observateurs="type_observateurs"
											:type_propriete="type_propriete"
											:type_proprietes="type_proprietes"
											:situation_topographie="situation_topographie"
											:situation_topographies="situation_topographies"
							                :type_mares="type_mares"
											:stade_evolution_mares="stade_evolution_mares"
											:usage_mares="usage_mares"
											:usage_dechets="usage_dechets"
											:situation_contextes="situation_contextes"
											:situation_batis="situation_batis"
											:situation_clotures="situation_clotures"
											:caracteristique_formes="caracteristique_formes"
											:caracteristique_eau_hauteurs="caracteristique_eau_hauteurs"
											:caracteristique_fond_mares="caracteristique_fond_mares"
											:caracteristique_berges="caracteristique_berges"
											:caracteristique_pietinements="caracteristique_pietinements"
											:hydrologie_regimes="hydrologie_regimes"
											:hydrologie_reseaux="hydrologie_reseaux"
											:hydrologie_alimentations="hydrologie_alimentations"
											:hydrologie_turbidites="hydrologie_turbidites"
											:hydrologie_tampons="hydrologie_tampons"
											:hydrologie_exutoires="hydrologie_exutoires"
											:hydrologie_presence_poissons="hydrologie_presence_poissons"
											:ecologie_boisements="ecologie_boisements"
											:ecologie_ombrages="ecologie_ombrages"
											:interventions="interventions"
											:taxon_faunes="taxon_faunes"
											:taxon_flores="taxon_flores"
											:taxon_eee_faunes="taxon_eee_faunes"
											:taxon_eee_flores="taxon_eee_flores"
											:utilisateur="utilisateur"
											:code_ofb="code_ofb"
											:statut_protections="statut_protections"
											:statut_protection="statut_protection"
											:observateurs="observateurs"
											:observateur="observateur"
											:caracterisations="caracterisations"
											v-on:checkIfIsLoading="checkIfIsLoading"
										></router-view>
									</div>
								</div>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		props: {
			heightProps: false,
		},
		data(){
			return{
				isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                base_url: base_url,
                height:this.heightProps ? this.heightProps : '100vh',

				nom_mare: '',
				errors: [],
				utilisateur: [],
				type_observateur: [],
				type_observateurs: [],
				type_propriete: [],
				type_proprietes: [],
				mare: [],
				fiche: [],

				type_mares: [],
				stade_evolution_mares: [],
				situation_topographie: [],
				situation_topographies: [],
				usage_mares: [],
				usage_dechets: [],
				situation_contextes: [],
				situation_batis: [],
				situation_clotures: [],
				caracteristique_formes: [],
				caracteristique_eau_hauteurs: [],
				caracteristique_fond_mares: [],
				caracteristique_berges: [],
				caracteristique_pietinements: [],
				hydrologie_regimes: [],
				hydrologie_reseaux: [],
				hydrologie_alimentations: [],
				hydrologie_turbidites: [],
				hydrologie_tampons: [],
				hydrologie_exutoires: [],
				hydrologie_presence_poissons: [],
				ecologie_boisements: [],
				ecologie_ombrages: [],
				interventions: [],
				taxon_faunes: [],
				taxon_flores: [],
				taxon_eee_faunes: [],
				taxon_eee_flores: [],

				utilisateur: [],
				observateurs: [],
				observateur: [],

				formulaires: [],
				code_ofb: '',
				code_ofb_is_save: '',

				caracterisations: [],

				statut_protection: '',
				statut_protections: [],
			}
		},
		computed: {
	    	computedHeight: function () {
	      		return this.height;
	    	}
	  	},
		methods:{
			saveCodeOfb(){
				let self = this;
				
				let data = {
					code_ofb: self.code_ofb,
					mare_id: self.mare.id
				}

				if(self.code_ofb != '' && self.mare && self.mare.id){
					axios.post(base_url+'/mes_mares/save_code_ofb', data)
			            .then(function (response) {
			                if(response.data.error == true){
		                        self.showErrors(self.parseErrorMessage(response.data.message));
			                }else{
			                	self.code_ofb_is_save = 'is-valid';
			                	setTimeout(() => {
			                		self.code_ofb_is_save = '';
			                	}, 3000)
			                }
			            })
			            .catch(function (error) { 
			                return self.checkError(error); 
			            });
				}
			},
			checkIfUserIsRedacteurOrObservateur: function(){
				if(this.utilisateur && this.mare.utilisateur_id == this.utilisateur.id || this.observateur && this.mare.observateur_id == this.observateur.id){
					return true;
				}
				return false;
			},
			newInfoMareAfterDrag: function(event){
				this.mare.departement_code = event.dep;
				this.mare.x_l93 = event.x_lambert93;
				this.mare.y_l93 = event.y_lambert93;
				this.mare.intercommunalite = event.raison_soc;
				this.mare.commune = event.nom;
			},
			checkIfUserCanChangeInput: function(input){
				console.log(this.utilisateur);
				if(this.utilisateur && this.utilisateur.role && this.utilisateur.role.nom_interne && (this.utilisateur.role.nom_interne == 'gestionnaire' || this.utilisateur.role.nom_interne == 'developpeur' || this.utilisateur.role.nom_interne == 'administrateur')){
					return true;
				}

				let form = this.formulaires.find(f => f.nom_interne === input);
				if(form && form.groupes && this.utilisateur != null && this.utilisateur != undefined  && this.utilisateur.groupe && this.utilisateur.groupe.id){
					let autorisation = form.groupes.find(g => g.id === this.utilisateur.groupe.id);
					if(autorisation != undefined){
						return true;
					}
				}

				return false;
			},
			checkIfIsLoading: function(event){
				this.isLoading = event;
			},
			createNouvelleFiche: function(){
				if(this.$route.name === 'mes_mares.consultation'){
					this.$router.push({path: '/mes_mares/consultation/'+ this.$route.params.id + '/nouvelle_fiche'});

				}else if(this.$route.name === 'mes_fiches.consultation'){
					this.$router.push({path: '/mes_fiches/consultation/'+ this.$route.params.id + '/nouvelle_fiche'});

				}else if(this.$route.name === 'recherche.consultation'){
					this.$router.push({path: '/recherche/consultation/'+ this.$route.params.id + '/nouvelle_fiche'});

				}else{
					this.$router.push({path: '/carte/consultation/'+ this.$route.params.id + '/nouvelle_fiche'});
				}
			},
			annulerNouvelleFiche: function(){
				if(this.$route.name === 'mes_mares.nouvelle_fiche'){
					this.$router.push({path: '/mes_mares/consultation/'+ this.$route.params.id});

				}else if(this.$route.name === 'mes_fiches.nouvelle_fiche'){
					this.$router.push({path: '/mes_fiches/consultation/'+ this.$route.params.id});

				}else if(this.$route.name === 'recherche.nouvelle_fiche'){
					this.$router.push({path: '/recherche/consultation/'+ this.$route.params.id});

				}else{
					this.$router.push({path: '/carte/consultation/'+ this.$route.params.id});
				}
			},
			closeMareComponent: function(){
				this.$destroy();

				if(this.$route.name === 'mes_mares.consultation' || this.$route.name == 'mes_mares.edit' || this.$route.name === 'mes_mares.nouvelle_fiche'){
					this.$router.push({path: '/mes_mares'});

				}else if(this.$route.name === 'mes_mares.fiche_edit' || this.$route.name === 'mes_fiches.consultation' || this.$route.name === 'mes_fiches.nouvelle_fiche'){
					this.$router.push({path: '/fiches_attentes'});

				}else if(this.$route.name == 'recherche.consultation' || this.$route.name == 'recherche.nouvelle_fiche'){
					this.$router.push({path: '/recherche'});

				}else if(this.$route.name == 'validation_mares.fiches'){
					this.$router.push({path: '/validation_mares'});

				}else if(this.$route.name === 'validation_fiches.fiches'){
					this.$router.push({path: '/validation_fiches'});

				}else{
					this.$router.push({path: '/carte'});
				}
			},
		},
		mounted: function(){
			const self = this;
			let route = '';

			// Consulter une mare ou valider une mare
			if(self.$route.name === 'mes_mares.consultation' || self.$route.name === 'validation_mares.fiches' || self.$route.name === 'mes_fiches.consultation'){
				route = self.base_url + '/mares/'+ self.$route.params.id + '/edit';
			// Valider une fiche
			}else if(self.$route.name === 'validation_fiches.fiches'){
				route = self.base_url + '/validation_fiches/'+ self.$route.params.id;
			// Consulter une fiche en attente
			}else if(self.$route.name === 'mes_mares.fiche_edit'){
				route = self.base_url + '/fiches_attentes/edit/' + self.$route.params.id;
			// consulter toutes les fiches d'une mare
			}else{
				route = self.base_url + '/mares/'+ self.$route.params.id;
			}

            axios.get(route)
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
            	self.utilisateur = resp.data.utilisateur;
            	self.mare = resp.data.mare;
            	self.formulaires = resp.data.formulaires;
            	self.observateurs = resp.data.observateurs;
            	self.observateur = resp.data.observateur;
            	self.caracterisations = resp.data.caracterisations;
            	self.statut_protections = resp.data.statut_protections;
            	self.hydrologie_presence_poissons = resp.data.hydrologie_presence_poissons;

            	if(self.mare){
            		let mare = self.mare;

            		self.type_observateur = self.type_observateurs.find(to => to.id == mare.type_observateur_id);
            		self.nom_mare = mare.nom;
            		self.type_propriete = self.type_proprietes.find(tp => tp.id == mare.type_propriete_id);
            		self.situation_topographie = self.situation_topographies.find(st => st.id == mare.situation_topographie_id);
            		self.code_ofb = mare.code_ofb;
            		self.statut_protection = self.statut_protections.find(s => s.id == self.mare.statut_protection_id);

            		self.fiche = resp.data.fiche;
            	}

            	self.isLoading = false;            	
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
</style>