<template>
	<div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
        	<div class="card">
	            <div class="card-header">
	                <h5><i class="far fa-lightbulb"></i> Idées d'améliorations</h5>
	            </div>
	            <div class="card-body">

	            	<p>Dans cet espace vous pouvez proposer des améliorations pour l'application PRAM.</p>
	            	<hr class="clearfix w-100">
	           
	           		<div v-if="!formulaire_soumis">
		                <form method="POST" action="" v-on:submit.prevent="create()">
		                	<div class="form-group row">
								<label class="col-2">Vos informations</label>
								<input type="text" class="form-control col-3 mr-4" readonly="readonly" disabled="disabled" :value="$user.nom + ' '+ $user.prenom">
								<input type="text" class="form-control col-3" readonly="readonly" disabled="disabled" :value="$user.email">
							</div>
							<div class="form-group row">
								<label class="col-2">Objet<span class="text-danger">*</span></label>
								<input type="text" class="form-control col-10" v-model="objet">
							</div>

							<div class="form-group row mt-4">
								<label class="col-2">Description<span class="text-danger">*</span></label>
								<textarea class="form-control col-10" rows="10" v-model="description"></textarea>
							</div>

							<div class="col-12 mt-4">
								<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
							</div>
		                </form>
		            </div>
		            <div class="w-100 d-flex justify-content-center" v-else>
		            	<div class="card text-white bg-success mb-3">
							<div class="card-header">Succès</div>
							<div class="card-body">
								<h5 class="card-title">Message envoyé</h5>
								<p class="card-text">Merci pour votre message. Nous allons traiter votre demande dans les plus brefs délais.</p>
							</div>
						</div>
		            </div>
	            </div>
	        </div>
	    </b-container>
	</div>
</template>


<script>
export default{
	data(){
		return{
			formulaire_soumis: false,
			objet: '',
			description: '',
			errors: [],
			isLoading: false,
		}
	},
	methods:{
		create: function(){
			let self = this;
			self.isLoading = true;
			self.errors = [];

			let formData = new FormData();

			if(!self.objet){
            	self.errors.push('Le champ Objet est obligatoire');
            }

            if(!self.description){
            	self.errors.push('Le champ Description est obligatoire');
            }
                 
            if(self.errors.length){
                self.showErrors(self.errors);
                self.isLoading = false;
                return false;
            }

            formData.append('objet', self.objet ? self.objet : '');
            formData.append('description', self.description ? self.description : '');
            formData.append('type', 'ameliorations');

            axios.post(base_url + '/create_tickets', formData, { 
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
						self.formulaire_soumis = true;
						self.isLoading = false;
                        // self.showSuccess([response.data.message]);
                        // self.$router.push({path: '/carte'});
                        // self.isLoading = false;
                        // return self.$emit('checkIfIsLoading', false);
                	}
                }
            })
            .catch(function (error) { 
                self.isLoading = false;
                return self.checkError(error); 
            });	
		}
	}
}
</script>