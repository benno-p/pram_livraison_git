<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Créer un nouvel observateur
                    </div>
                    <div class="card-body">
                        <form method="POST" v-on:submit.prevent="create()">
                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Nom<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" v-model="nom">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Prénom<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" v-model="prenom">
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

                                <router-link :to="'/observateurs'" class="btn btn-light">
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
                prenom: '',
                organisme: '',
                nom_source: '',
                errors: [],
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

                if(!self.prenom){
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
                        return self.$router.push({path: '/observateurs'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
        },
        mounted(){
            this.isLoading = false;
            // const self = this;
            // axios.get(base_url + '/observateurs/create')
            // .then(function (resp) {
            //     self.isLoading = true;
            //     self.departements = resp.data.departements;
            //     self.intercommunalites = resp.data.intercommunalites;
            //     self.communes = resp.data.communes;
            //     self.types = resp.data.types;
            //     self.isLoading = false;
            // })
            // .catch(function (resp) {
            //     return self.checkError(resp);
            // });
        }
    };
</script>
