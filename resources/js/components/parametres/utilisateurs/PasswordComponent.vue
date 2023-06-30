<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 90vh!important;">
            <div class="card p-4">
                <div class="mt-4">
                    <div class="card col-12 col-md-12 p-0 position-relative">
                        <div class="card-header">
                            Modifier mon mot de passe
                        </div>
                        <div class="card-body">
                            <form method="POST" v-on:submit.prevent="edit()">
                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Mot de passe actuel<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <input type="password" autocomplete="off" class="form-control" id="now_password" aria-describedby="now_password" v-model="now_password" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Nouveau mot de passe<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <input type="password" autocomplete="off" class="form-control" id="password" aria-describedby="password" v-model="password" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Confirmation du nouveau mot de passe</label>
                                    <div class="col-12 col-md-9">
                                        <input type="password" autocomplete="off" class="form-control" id="password_confirmation" aria-describedby="password_confirmation" v-model="password_confirmation" />
                                    </div>
                                </div>

                                <div class="col-12 mt-4 p-0">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                                    <router-link to="/" class="btn btn-light">
                                        <i class="fas fa-times-circle"></i> Retour
                                    </router-link>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </b-container>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isLoading: false,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                errors: [],
                now_password: '',
                password: '',
                password_confirmation: '',
            }
        },
        methods: {
            edit:function(){
                let self = this;
                let formData = new FormData();
                self.errors = [];

                if(!self.now_password){
                    self.errors.push('Le champ Mot de passe actuel est obligatoire');
                }

                if(!self.password){
                    self.errors.push('Le champ Nouveau mot de passe est obligatoire');
                }

                if(!self.password_confirmation){
                    self.errors.push('Le champ Confirmation du nouveau mot de passe est obligatoire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('now_password', self.now_password ? self.now_password : '');
                formData.append('password', self.password ? self.password : '');
                formData.append('password_confirmation', self.password_confirmation ? self.password_confirmation : '');

                axios.post(base_url + '/password', formData, { 
                    _method: 'POST',
                })
                .then(function (response) {
                    console.log(response);
                    if(response.data.error == true){
                        Object.keys(response.data.message).map(function(objectKey, index) {
                            let value = response.data.message[objectKey];
                            self.errors.push(value[0]);
                        });
                        self.showErrors(self.errors);
                        return false;
                    }else{
                        self.showSuccess(response.data.message);
                        self.$router.push({path: '/'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
            detectMobile: function(event){
                this.isMobile = event;
            },
        },
        mounted:function(){
            // const self = this;
            // self.isLoading = true;
            //  axios.get(base_url + '/membres/'+ this.$route.params.id + '/edit')
            // .then(function (resp) {
            //     self.membre = resp.data.membre;
            //     self.fonctions = resp.data.fonctions;

            //     if(self.membre){
            //         self.nom = self.membre.nom;
            //         self.prenom = self.membre.prenom;
            //         self.email = self.membre.email;
            //         self.telephone_fixe = self.membre.telephone_fixe;
            //         self.telephone_portable = self.membre.telephone_portable;

            //         self.membre.fonctions.forEach(function(fonction){
            //             self.fonctions_selected.push(fonction.id);
            //         });
            //     }
            //     self.isLoading = false;
            // })
            // .catch(function (resp) {
            //     return self.checkError(resp);
            // });
        },
    };
</script>
