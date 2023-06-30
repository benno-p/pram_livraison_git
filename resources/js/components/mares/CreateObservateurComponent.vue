<template>
	<modal name="modal_create_observateur" v-bind:click-to-close="false" scrollable :adaptive="true" :focusTrap="true" width="1000" height="auto" style="z-index: 9999999999;">
		<div class="modal-perso" style="overflow: auto;">
			<div class="vld-parent">
	            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
	        </div>
	        <div class="card">
	    		<b-card-header class="text-center border-0 pt-4 pt-md-4 pb-0">
			     	<div class="col-12 text-left">
			        	<button class="btn btn-sm btn-primary float-right" @click="hide('modal_create_observateur')">
			        		<i class="fas fa-times-circle"></i> Fermer
			        	</button>
			        	<p class="h3">Créer un nouvel observateur</p>
			      </div>
			    </b-card-header>

			    <div class="card-body" v-if="!isLoading">
			    	<form method="POST" v-on:submit.prevent="create()">
                        <div class="form-group row">
                            <label for="nom" class="col-12 col-md-2 col-form-label">Nom<span class="text-danger">*</span></label>
                            <div class="col-12 col-md-10">
                                <input type="text" class="form-control mb-2" v-model="nom" ref="nom">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nom" class="col-12 col-md-2 col-form-label">Prénom<span class="text-danger">*</span></label>
                            <div class="col-12 col-md-10">
                                <input type="text" class="form-control mb-2" v-model="prenom" ref="prenom">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nom" class="col-12 col-md-2 col-form-label">Organisme</label>
                            <div class="col-12 col-md-10">
                                <input type="text" class="form-control mb-2" v-model="organisme">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nom" class="col-12 col-md-2 col-form-label">Nom source</label>
                            <div class="col-12 col-md-10">
                                <input type="text" class="form-control mb-2" v-model="nom_source">
                            </div>
                        </div>

                        <div class="col-12 mt-4 p-0">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                            <router-link :to="before_route" class="btn btn-light">
                                <i class="fas fa-times-circle"></i> Retour
                            </router-link>
                        </div>  
                    </form>
			    </div>
			</div>
		</div>
	</modal>
</template>

<script>
	export default{
		props: ['before_route'],
		data(){
			return{
				base_url: base_url,
				isLoading: true,
            	fullPage: false,
            	errors: [],

            	nom: '',
                prenom: '',
                organisme: '',
                nom_source: '',

			}
		},
		methods: {
			create: function(){
	        	let self = this;
                let formData = new FormData();
                self.errors = [];

                this.resetColorInputError(this.$refs);

                if(!self.nom){
                	this.colorInputError(this.$refs, 'nom');
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(!self.prenom){
                	this.colorInputError(this.$refs, 'prenom');
                    self.errors.push('Le champ Prénom est obligatoire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');
                formData.append('prenom', self.prenom ? self.prenom : '');
                formData.append('organisme', self.organisme ? self.organisme : '');
                formData.append('nom_source', self.nom_source ? self.nom_source : '');

                axios.post(base_url + '/observateurs', formData, { 
                    _method: 'POST',
                })
                .then(function (response) {
                    if(response.data.error == true){
                        Object.keys(response.data.message).map(function(objectKey, index) {
                            let value = response.data.message[objectKey];
                            self.errors.push(value[0]);
                        });
                        self.showErrors(self.errors);
                        return false;
                    }else{
                        self.showSuccess(response.data.message);
                        self.$emit('addNewObservateur', response.data.observateur);
                        return self.hide();
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
	        },
			show: function(modal_name) {
	            this.$modal.show(modal_name);
	        },
	        hide: function() {
	        	this.$destroy();
	            this.$modal.hide('modal_create_observateur');
	            return this.$router.push({path: this.before_route});            
	        },
		},
		mounted(){
			this.show('modal_create_observateur');
			this.isLoading = false;
		}
	}
</script>