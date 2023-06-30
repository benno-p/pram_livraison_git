<template>
	<div>
		<b-dropdown button variant="link" id="dropdown-header" right no-caret>
			<template #button-content class="tetatztz">
			  	<i class="fas fa-bell text-white fa-2x mr-0"></i>
				<span class="badge badge-light ml-0 bg-danger text-white" v-if="notifs === true">!</span>
			</template>
			<b-dropdown-header id="dropdown-header-label">
		     	<p class="h3 text-primary"><i class="fas fa-bell"></i> Notifications</p>
		    </b-dropdown-header>
			<b-dropdown-text class="dropdown-text-perso">
				<b-card no-body class="pt-1 pb-4 pl-4 pr-4">
					<b-card-text>

						<template v-if="messages_non_lus != 0">
							<div class="">
				                <div class="">
			                		<label>Chat</label>
			                	</div>
			                </div>
							<div class="col-12 card card-stats mb-4 p-0">
	            				<div class="d-flex">
	            					<div class="d-flex align-items-center justify-content-between w-100 p-1">
		                    			<div class="col d-flex flex-column p-0">
			                    			<p class="h4 card-title text-uppercase mb-0">
			                    				Vous avez un ou plusieurs messages non lus
			                    			</p>
			                    		</div>
			                    		<i class="fas fa-comments"></i>
									</div>
								</div>

								<hr class="m-0">

								<div class="d-flex justify-content-between pl-4 pr-4 pt-2 pb-2">
									<router-link :to="'/chat'" class="btn btn-dark btn-sm">
	                                    <i class="fas fa-comments"></i> Aller au chat
	                                </router-link>
								</div>
	                    	</div>
	                    </template>

	                    <div class="">
			                <div class="">
		                		<label>Invitations à des rendez-vous</label>
		                	</div>
		                </div>

						<p v-if="agenda_invitations.length == 0" class="h4 card-title text-uppercase mb-0">
							Aucune invitation pour le moment
						</p>
					 	<div class="col-12 card card-stats mb-4 p-0" v-for="agenda in agenda_invitations">
            				<div class="d-flex">
            					<div class="d-flex align-items-center justify-content-between w-100 p-1">
	                    			<div class="col d-flex flex-column p-0">
		                    			<p class="h4 card-title text-uppercase mb-0">
		                    				{{convertDateWithDayName(agenda.debut)}} 
		                    				<span class="text-lowercase">{{convertHeure(agenda.debut)}} - {{convertHeure(agenda.fin)}}</span>
		                    			</p>
		                    			<p class="text-orange mb-0">
		                    				<small>
		                    					<span class="text-dark">Sujet : </span>{{agenda.sujet}} 
		                    					<span v-if="agenda.lieu">
		                    						<span class="text-dark"> - Lieu : </span>{{agenda.lieu}}
		                    					</span>	
		                    				</small>
		                    			</p>
		                    			<p class="text-success mb-0">
		                    				<small>
		                    					<span class="text-dark">Invité par : </span>
		                    					{{agenda.utilisateur_id && agenda.utilisateur ? agenda.utilisateur.prenom+' '+agenda.utilisateur.nom : ''}} 
		                    				</small>
		                    			</p>
		                    		</div>
		                    		<span class="avatar avatar-sm rounded-circle">
										<img alt="Image placeholder" src="images/user.jpg">
									</span>
								</div>
							</div>

							<hr class="m-0">

							<div class="d-flex justify-content-between pl-4 pr-4 pt-2 pb-2">
								<button class="btn btn-success btn-sm" @click.prevent="submitAgenda(agenda, 1)">
									<i class="fas fa-check-circle"></i> Accepter
								</button>
								<button class="btn btn-danger btn-sm" @click.prevent="submitAgenda(agenda, 0)">
									<i class="fas fa-times-circle"></i> Refuser
								</button>
								<router-link :to="'/agenda'" class="btn btn-warning btn-sm">
                                    <i class="fas fa-calendar-check"></i> Voir Calendrier
                                </router-link>
							</div>
                    	</div>
					</b-card-text>
				</b-card>
			</b-dropdown-text>
		</b-dropdown>
	</div>
</template>

<script>
	export default{
		props:['notifications'],
		data(){
			return{
				isLoading: true,
				agenda_invitations: [],
				notifs: this.notifications,
				messages_non_lus: [],
			}
		},
		watch:{
			notifications: function(val){
				// console.log(val)
				this.notifs = val;
			}
		},
		methods:{
			submitAgenda: function(agenda, reponse){
				let self = this;
				self.errors = [];
	  			let formData = new FormData();

	  			formData.append('agenda_id', agenda && agenda.id ? agenda.id : '');
	  			formData.append('reponse', reponse);

				axios.post(base_url + '/agendas/agenda_invitation', formData, { 
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
	                	let agenda_index = self.agenda_invitations.findIndex(a => a.id == agenda.id);
	                	if(agenda_index >= 0){
	                		self.agenda_invitations.splice(agenda_index, 1);

	                		if(self.agenda_invitations.length == 0){
                				self.notifs = false;
                				self.$emit('changeNotifications', false);
                			}

	                		if(self.$route.name === 'agenda'){
	                			self.$emit('reloadAgenda', true);
	                		}
	                	}
	                }
	            })
	            .catch(function (error) { 
	            	return self.checkError(error);
	            });
			},
			loadNotifications: function(){
				const self = this;
				axios.get(base_url + '/load_notifications')
				.then(function (resp) {
					self.agenda_invitations = resp.data.agenda_invitations;
					self.messages_non_lus = resp.data.messages_non_lus;
					self.isLoading = false;
				})
				.catch(function (resp) {
					return self.checkError(resp);
				});
			}
		},
		mounted(){
			let self = this;
			this.$root.$on('bv::dropdown::show', bvEvent => {
		    	self.loadNotifications();
		    })
		}
	}
</script>