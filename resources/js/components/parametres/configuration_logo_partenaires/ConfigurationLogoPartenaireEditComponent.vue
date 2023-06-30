<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Créer un nouveau partenaire
                    </div>
                    <div class="card-body" v-if="!isLoading">
                        <form method="POST" v-on:submit.prevent="create()">
                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Nom du partenaire<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" v-model="nom">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">URL site internet</label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" v-model="site_url">
                                </div>
                            </div>

                            <file-upload-component
                            :titre="'Logo'"
                            :accept="'.jpg,.JPG,.png,.PNG,.jpeg,.JPEG'"
                            :label="'Logo (format jpg, jpeg, png - 5mo maximum)'"
                            :fichier_exist="logo_url"
                            :required="true"
                            v-on:fichierFromFileUploadComponent="logoFromFileUploadComponent"
                            ></file-upload-component>


                            <div class="col-12 mt-4 p-0">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                                <router-link :to="'/configuration_logo_partenaires'" class="btn btn-light">
                                    <i class="fas fa-times-circle"></i> Retour
                                </router-link>
                            </div>  
                        </form>
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
                isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                base_url: base_url,
                errors: [],
                
                partenaire: [],
                nom: '',
                site_url: '',

                logo: '',
                logo_url: '',
                modifier_logo: false,
            }
        },
        methods: {
            logoFromFileUploadComponent: function(event){
                this.logo = event.fichier;
                this.modifier_logo = event.modifier_fichier;
            },
            create:function(){
                let self = this;
                let formData = new FormData();
                self.errors = [];

                if(!self.nom){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(self.logo_url === '' && !self.logo){
                    self.errors.push('Le champ Logo est obligatoire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');
                formData.append('site_url', self.site_url ? self.site_url : '');
                formData.append('logo', self.logo ? self.logo : '');
                formData.append('modifier_logo', self.modifier_logo === true ? '1' : '0');

                formData.append('_method', 'PUT');
                axios.post(base_url + '/configuration_logo_partenaires/' + self.partenaire.id, formData, {  
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
                        return self.$router.push({path: '/configuration_logo_partenaires'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
            detectMobile: function(event){
                this.isMobile = event;
            }
        },
        mounted(){
            const self = this;
            axios.get(base_url + '/configuration_logo_partenaires/'+ this.$route.params.id + '/edit')
            .then(function (resp) {
                self.isLoading = true;
                self.partenaire = resp.data.partenaire;

                if(self.partenaire){
                    self.nom = self.partenaire.nom;
                    self.site_url = self.partenaire.site_url ? self.partenaire.site_url : '';
                    self.logo_url = self.partenaire.logo ? self.base_url + '/configuration/logo_partenaires/' + self.partenaire.logo : '';
                }

                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        }
    };
</script>
