<template>
	<div>
		<div class="row">
           	<div class="col-12">
                <div class="w-100">
		            <div class="card">
		                <div class="card-header">
		                	Connexion
		                	<button type="button" class="close" aria-label="Close" @click="closeConnexionComponent()">
					        	<span aria-hidden="true">&times;</span>
					        </button>
		                </div>

		                <div class="card-body">
		                    <form method="POST" action="" v-on:submit.prevent="connexion()">
		                        <div class="form-group row">
		                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
		                            <div class="col-md-6">
		                                <input id="email" type="email" class="form-control" name="email" required v-model="email">
		                            </div>
		                        </div>

		                        <div class="form-group row">
		                            <label for="password" class="col-md-4 col-form-label text-md-right">Mot de passe</label>
		                            <div class="col-md-6">
		                                <input id="password" type="password" class="form-control" required v-model="password">
		                            </div>
		                        </div>

		                        <div class="form-group row mb-0">
		                            <div class="col-md-8 offset-md-3">
		                                <button type="submit" class="btn btn-primary">
		                                    Connexion
		                                </button>
		                                <router-link to="/" class="btn btn-light">
	                                        <i class="fas fa-times-circle"></i> Retour
	                                    </router-link>
	                                    <a href="#" class="btn btn-link" @click="passwordLost()">
		                                	Mot de passe oublié ?
		                                </a>
		                            </div>
		                        </div>
		                    </form>
		                </div>
		            </div>
			    </div>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				email: '',
				password: '',
				isLoading: false,
				errors: [],
			}
		},
		methods:{
			passwordLost: function(){
				let url = base_url + '/password/reset';
				return window.location = url;
			},
			connexion: function(){
				let self = this;
				let formData = new FormData;
				self.errors = [];

				formData.append('email', self.email ? self.email : '');
				formData.append('password', self.password ? self.password : '');

				axios.post(base_url + '/login', formData, { 
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
                    	if(response.data.error == false){
                    		window.location = response.data.url;
                    	}
                        self.isLoading = false;
                    }
                })
                .catch(function (error) { 
                    self.isLoading = false;
                    return self.checkError(error); 
                });
			},
			closeConnexionComponent: function(){
				this.$router.push({path: '/'});
				return this.$destroy();
			},
		},


	}



</script>