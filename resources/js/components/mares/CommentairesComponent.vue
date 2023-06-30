<template>
	<modal name="modal_commentaires" v-bind:click-to-close="false" scrollable :adaptive="true" :focusTrap="true" width="1000" height="auto">
		<div class="modal-perso" style="overflow: auto;">
			<div class="vld-parent">
	            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
	        </div>
	        <div class="card">
	    		<b-card-header class="text-center border-0 pt-4 pt-md-4 pb-0">
			     	<div class="col-12 text-left">
			        	<button class="btn btn-sm btn-primary float-right" @click="hide('modal_commentaires')">
			        		<i class="fas fa-times-circle"></i> Fermer
			        	</button>
			        	<p class="h3">Commentaires</p>
			      </div>
			    </b-card-header>

			    <div class="card-body" v-if="!isLoading">
			    	<form v-on:submit.prevent="sendMessage()">
				    	<template v-if="commentaires.length > 0">
					    	<div :class="commentaire.utilisateur_send === true ? 'col-12 message-card-utilisateur' : 'col-12 message-card-validateur'" v-for="commentaire in commentaires">
					    		<div class="message-card">
					    			{{commentaire.utilisateur_id ? commentaire.utilisateur.prenom+' '+commentaire.utilisateur.nom : ''}}
					    		</div>
					    		<div class="message-card mt-1">
					    			<p>	
										{{capitalizeFirstLetter(commentaire.message)}}
										<sub>
											{{convertDateHeure(commentaire.created_at)}}
										</sub>
									</p>
								</div>
								<div class="form-check message-card" v-if="commentaire.validateur_send">
									<input class="form-check-input" type="checkbox" :value="true" :id="'checkbox_commentaire_lu_'+commentaire.id" v-model="commentaire.utilisateur_vu" @change="updateCommentaireLu(commentaire)">
									<label class="form-check-label" :for="'checkbox_commentaire_lu_'+commentaire.id">
										Lu
									</label>
								</div>
								<hr>		    	
							</div>
					    </template>

					    <template v-else>
					    	<div class="col-12 mb-4">
					    		<p>Aucun commentaire</p>
					    	</div>
					    </template>

					    <div class="col-12" v-if="statut && statut.nom_interne != 'valider'">
				    		<textarea class="form-control" placeholder="Votre message" v-model="message"></textarea>
				    	</div>

				    	<div class="card-footer text-right pt-4 bg-white" style="border: none!important">
		                	<button class="btn btn-success" type="submit" v-if="statut && statut.nom_interne != 'valider'">
		                		Répondre
		                	</button>
		                	<button class="btn btn-secondary" @click.prevent="hide('modal_commentaires', false)">
		                		Fermer
		                	</button>
		                </div>
		            </form>
			    </div>
			</div>
		</div>
	</modal>
</template>

<script>
	export default{
		props: ['mare', 'fiche', 'type'],
		data(){
			return{
				base_url: base_url,
				isLoading: true,
            	fullPage: false,
            	message: '',
            	errors: [],
            	commentaires: [],
            	statut:[],
            	route_update_commentaire: '',
            	route_send_message: '',
			}
		},
		methods: {
			sendMessage: function(){
	        	let self = this;
                let formData = new FormData();
                self.errors = [];

                if(self.message === ''){
                	self.errors.push('Le champ Message est obligatoire');
                }

                if(self.errors.length){
	                self.showErrors(self.errors);
	                self.isLoading = false;
	                return false;
	            }

	            formData.append('mare_id', self.mare && self.mare.id ? self.mare.id : '');
	            formData.append('fiche_id', self.fiche && self.fiche.id ? self.fiche.id : '');
	            formData.append('message', self.message ? self.message : '');

	            axios.post(base_url + self.route_send_message, formData, {
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
                    	if(self.type && self.type === 'mare'){
                    		self.$emit('updateMare', response.data.mare);
                    	}else if(self.type && self.type === 'fiche'){
                    		self.$emit('updateFiche', response.data.fiche);
                    	}
                    	self.hide();
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
	        },
	        updateCommentaireLu: function(commentaire){
	        	let self = this;
	        	let formData = new FormData();

	        	formData.append('commentaire_id', commentaire && commentaire.id ? commentaire.id : '');
	        	formData.append('utilisateur_vu', commentaire && commentaire.utilisateur_vu === false ? 0 : 1);

	        	axios.post(base_url + self.route_update_commentaire, formData, {
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
                    	if(self.type && self.type === 'mare'){
                    		self.$emit('updateMare', response.data.mare);
                    	}else if(self.type && self.type === 'fiche'){
                    		self.$emit('updateFiche', response.data.fiche);
                    	}
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
	            this.$modal.hide('modal_commentaires');

	            if(this.type === 'mare'){
	            	return this.$router.push({path: '/mes_mares'});
	            }else if(this.type === 'fiche'){
	            	return this.$router.push({path: '/fiches_attentes'});
	            }	            
	        },
		},
		mounted(){
			if(this.type && this.type === 'mare'){
				this.commentaires = this.mare.commentaires;
				this.statut = this.mare.statut;
				this.route_send_message = '/mes_mares/send_commentaire';
				this.route_update_commentaire = '/mes_mares/update_commentaire_lu';
			}else if(this.type && this.type === 'fiche'){
				this.commentaires = this.fiche.commentaires;
				this.statut = this.fiche.statut;
				this.route_send_message = '/mes_fiches/send_commentaire';
				this.route_update_commentaire = '/mes_fiches/update_commentaire_lu';
			}

			this.show('modal_commentaires');
			this.isLoading = false;
		}
	}
</script>