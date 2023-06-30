<template>
	<div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

		<b-container fluid class="mt--9" style="min-height: 90vh!important;">
		    <div class="row mt-4">
		        <div class="col-12">
		            <div class="card mb-4 pb-4">
		                <div class="card-header">
		                	<button type="button" class="close" aria-label="Close" @click="closeMareComponent()">
					        	<span aria-hidden="true">&times;</span>
					        </button>
		                	Valider une Fiche
		                </div>
		                <div class="card-body">
		                	<form method="POST" action="" v-on:submit.prevent="valide_fiche()">
			                	<div class="d-flex flex-wrap justify-content-between">
				                	<div class="col-12 col-md-4" style="z-index:9999">
				                		<multiselect 
											v-model="statut"
											:options="statuts" 
											deselectLabel="" 
											track-by="id" 
											label="nom"
											placeholder="Statut"
											selectLabel=""
											:searchable="true" 
											:allow-empty="false"
											>
										</multiselect>
					                </div>
					                <div class="col-12 col-md-4" v-if="statut && statut.nom_interne === 'en_attente_de_validation' || statut && statut.nom_interne === 'refuser'">
					                	<textarea class="form-control" placeholder="Commentaire" v-model="commentaire"></textarea>
					                </div>
					                <div class="col-12 col-md-4" v-if="statut && statut.nom_interne === 'valider'">
					                	<select class="form-control"  v-model="caracterisation" required="required">
					                		<option selected="selected" disabled="disabled" :value="null">-- Caractérisation --</option>
					                		<option v-for="caracterisation in caracterisations" :value="caracterisation.id">
					                			{{caracterisation.nom}}
					                		</option>
					                	</select>
					                	<p class="pt-2">{{caracterisation_actuelle && caracterisation_actuelle.nom ? 'Caractérisation actuelle : Mare '+caracterisation_actuelle.nom : ''}}</p>
					                </div>
					                <div class="col-12 col-md-4">
					                	<button class="btn btn-success">
					                		Enregistrer
					                	</button>
					                </div>
				                </div>
				            </form>
		                </div>
		            </div>
		        </div>
		    </div>

		    <router-view 
		    name="four"
		    :heightProps="'70vh'"
		    ></router-view>
		</b-container>
	</div>
</template>


<script>
export default{
	data(){
		return{
			isLoading: true,
            fullPage: false,
            windowWidth: window.innerWidth,
            isMobile: false,
            base_url: base_url,

            errors: [],
            valide: null,
			commentaire: '',
			caracterisations: [],
			caracterisation: null,

			statuts: [],
			statut: [],

			caracterisation_actuelle: [],
        }
    },
    methods: {
    	closeMareComponent: function(){
			this.$destroy();
			this.$router.push({path: '/validation_fiches'});
		},
		detectMobile: function(event){
            this.isMobile = event;
        },
		valide_fiche: function(){
			let self = this;
			let formData = new FormData;
			self.errors = [];

			// if(self.valide === null){
   //              self.errors.push('Le Champ Valide est obligatoire');
   //          }
   			if(self.objectSize(self.statut) === 0){
   				self.errors.push('Le champ statut est obligatoire');
   			}

            if(self.statut && self.statut.nom_interne && self.statut.nom_interne === 'refuser' && self.commentaire == ''){
                self.errors.push('Le Commentaire est obligatoire si vous refusez la mare');
            }

            if(self.statut && self.statut.nom_interne && self.statut.nom_interne === 'en_attente_de_validation' && self.commentaire == ''){
                self.errors.push('Le Commentaire est obligatoire si vous laissez le statut En attente de validation');
            }

            if(self.statut && self.statut.nom_interne && self.statut.nom_interne === 'valider' && self.caracterisation == null){
                self.errors.push('Le champ Caracterisation est obligatoire si vous acceptez la mare');
            }

            if(self.errors.length){
                self.showErrors(self.errors);
                self.isLoading = false;
                return false;
            }


            // formData.append('valide', self.valide || self.valide == 0 ? self.valide : '');
            formData.append('statut_id', self.statut && self.statut.id ? self.statut.id : '');
            formData.append('commentaire', self.commentaire ? self.commentaire : '');
            formData.append('fiche_id', this.$route.params.id ? this.$route.params.id : '');
            formData.append('caracterisation_id', this.caracterisation ? this.caracterisation : '');
            formData.append('validation_type', 'fiche');

            axios.post(base_url + '/validation_fiches', formData, { 
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
                    self.isLoading = false;
                    self.showSuccess([response.data.message]);
                    self.$router.push({path: '/validation_fiches'});
                }
            })
            .catch(function (error) { 
                self.isLoading = false;
                return self.checkError(error); 
            });
		},
    },
    mounted:function(){
        const self = this;
        self.isLoading = true;
        axios.get(base_url + '/validation_fiches/'+this.$route.params.id+'/edit')
        .then(function (resp) {
            self.all_data = resp.data;
            self.caracterisations = resp.data.caracterisations;
            self.statuts = resp.data.statuts;

            let mare = resp.data.mare;
            // self.caracterisation = mare.caracterisation_id;

            self.caracterisation_actuelle = self.caracterisations.find(c => c.id === mare.caracterisation_id);

            if(resp.data.fiche && resp.data.fiche.statut && resp.data.fiche.statut.id){
            	self.statut = self.statuts.find(s => s.id === resp.data.fiche.statut.id);
            }

            self.isLoading = false;            
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    },
}
</script>