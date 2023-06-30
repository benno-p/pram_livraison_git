<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Ajouter un taxon {{taxon_type}}
                    </div>
                    <div class="card-body">
                        <form method="POST" v-on:submit.prevent="create()">
                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Nom<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" v-model="nom">
                                </div>
                            </div>

                            <div class="col-12 mt-4 p-0">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                                <router-link :to="'/' + url" class="btn btn-light">
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
                nom: '',
                errors: [],
                taxon_type: '',
                url: '',
            }
        },
        methods: {
            detectMobile: function(event){
                this.isMobile = event;
            },
            create:function(){
                let self = this;
                let formData = new FormData();
                self.errors = [];

                if(!self.nom){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');

                axios.post(base_url + '/' + self.url, formData, { 
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
                        return self.$router.push({path: '/' + self.url});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
        },
        mounted:function(){
            let self = this;
            if(self.$route.name == 'taxon_faunes.create'){
                self.taxon_type = 'Faune';
                self.url = 'taxon_faunes';
            }else if(self.$route.name == 'taxon_flores.create'){
                self.taxon_type = 'Flore';
                self.url = 'taxon_flores';
            }else if(self.$route.name == 'taxon_eee_faunes.create'){
                self.taxon_type = 'EEE Faune';
                self.url = 'taxon_eee_faunes';
            }else if(self.$route.name == 'taxon_eee_flores.create'){
                self.taxon_type = 'EEE Flore';
                self.url = 'taxon_eee_flores';
            } 
            this.isLoading = false;
        },
    };
</script>
