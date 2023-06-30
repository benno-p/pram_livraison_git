<template>
    <div class="position-relative p-4" ref="container">
        <is-mobile ref="isMobileComponent" v-on:detectMobile="detectMobile"></is-mobile>
        <div class="vld-parent">
            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
        </div>
        <div class="col-md-12 p-0">
            <div class="w-100">
                <h2 class="mb-4">Demande de création de compte</h2>

                <div class="mt-4">
                    <div class="card col-12 col-md-12 p-0 position-relative">
                        <div class="card-body">
                            <form method="POST" v-on:submit.prevent="create()" class="text-left">
                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Nom<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <input type="text" class="form-control mb-2" v-model="nom" required="required">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Prénom<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <input type="text" class="form-control mb-2" v-model="prenom" required="required">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">E-mail<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <input type="email" class="form-control mb-2" v-model="email" required="required">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Téléphone Fixe</label>
                                    <div class="col-12 col-md-9">
                                        <input type="text" class="form-control mb-2" v-model="tel_fixe">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Téléphone Portable</label>
                                    <div class="col-12 col-md-9">
                                        <input type="text" class="form-control mb-2" v-model="tel_portable">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Quel est votre organisme de rattachement ?<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <textarea class="form-control" v-model="organisme" required="required"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-3 col-form-label">Motivation de votre demande<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-9">
                                        <textarea class="form-control" v-model="motivation" required="required"></textarea>
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
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                nom: '',
                prenom: '',
                email: '',
                errors: [],
                tel_fixe: '',
                tel_portable: '',
                organisme: '',
                motivation: '',
            }
        },
        methods: {
            detectMobile: function(event){
                this.isMobile = event;
            },
            create:function(){
                let self = this;
                self.isLoading = true;
                let formData = new FormData();
                self.errors = [];

                if(!self.nom){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(!self.prenom){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(!self.email){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(!self.organisme){
                    self.errors.push('Le champ Organisme est obligatoire');
                }

                if(!self.motivation){
                    self.errors.push('Le champ Motivation de votre demande est obligatoire');
                }

                if(self.errors.length){
                    self.isLoading = false;
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');
                formData.append('prenom', self.prenom ? self.prenom : '');
                formData.append('email', self.email ? self.email : '');
                formData.append('tel_fixe', self.tel_fixe ? self.tel_fixe : '');
                formData.append('tel_portable', self.tel_portable ? self.tel_portable : '');
                formData.append('organisme', self.organisme ? self.organisme : '');
                formData.append('motivation', self.motivation ? self.motivation : '');

                axios.post(base_url + '/demande_comptes', formData, { 
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
                        self.showSuccess(response.data.message);
                        self.isLoading = false;
                        return self.$router.push({path: '/'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
        },
        mounted:function(){
            let self = this;
            this.isLoading = false;
        },
    };
</script>
