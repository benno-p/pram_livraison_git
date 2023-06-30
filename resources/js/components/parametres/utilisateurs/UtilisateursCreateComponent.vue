<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="mt-4">
                    <div class="col-12 col-md-12 p-0 position-relative">
                        <div class="w-100">
                            <form method="POST" v-on:submit.prevent="create()">
                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Nom<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <input type="text" class="form-control mb-2" v-model="nom" @change="checkIfObservateurExist()">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Prénom<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <input type="text" class="form-control mb-2" v-model="prenom" @change="checkIfObservateurExist()">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="match_observateur">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Associer observateur<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <div class="alert alert-warning" role="alert">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <div>
                                                    {{match_observateur_message}}
                                                </div>
                                                <div class="pl-4">
                                                    <select class="form-control" v-model="associer_observateur">
                                                        <option selected disabled :value="null">-- Sélection --</option>
                                                        <option :value="true">Oui</option>
                                                        <option :value="false">Non</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">E-mail<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <input type="email" class="form-control mb-2" v-model="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Rôle<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <multiselect
                                        @input="departement = []"
                                        v-model="role_selected" 
                                        deselectLabel="Cliquez pour enlever" 
                                        track-by="id" 
                                        label="nom" 
                                        placeholder="Sélectionner un rôle"
                                        selectLabel="Cliquez pour sélectionner"
                                        :options="roles" 
                                        :searchable="false" 
                                        :allow-empty="false">
                                        </multiselect>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Groupe</label>
                                    <div class="col-12 col-md-10">
                                        <multiselect
                                        v-model="groupe_selected"
                                        :options="groupes" 
                                        deselectLabel="Cliquez pour enlever" 
                                        track-by="id" 
                                        label="nom" 
                                        placeholder="Sélectionner un groupe"
                                        selectLabel=""
                                        :searchable="false" 
                                        :allow-empty="true">
                                        </multiselect>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="role_selected.nom_interne == 'gestionnaire' || role_selected.nom_interne == 'administrateur'">
                                    <div class="col-12 col-md-12 p-0">
                                        <label for="nom" class="col-12 col-form-label">Départements<span class="text-danger">*</span></label>
                                        <div class="d-flex flex-wrap col-12">
                                            <div v-for="dep in departements" class="custom-control custom-checkbox mb-2 col-12 col-md-3">
                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_dep' + dep.id" :value="dep" v-model="departement" 
                                                >
                                                <label class="custom-control-label" :for="'checkbox_dep' + dep.id">{{dep.nom}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="col-12 mt-4 p-0">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                                    <router-link to="/utilisateurs" class="btn btn-light">
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
                nom: '',
                prenom: '',
                email: '',
                roles: [],
                role_selected: [],
                departements: [],
                departement:[],
                groupes: [],
                groupe_selected: [],

                match_observateur: false,
                match_observateur_message: '',
                observateur: [],
                associer_observateur: null,
            }
        },
        methods: {
            checkIfObservateurExist: function(){
                let self = this;
                let formData = new FormData();

                formData.append('nom', self.nom);
                formData.append('prenom', self.prenom);

                axios.post(base_url + '/utilisateurs/check_if_observateur_exist', formData, { 
                    _method: 'POST',
                })
                .then(function (response) {
                    if(response.data.match == true){
                        self.match_observateur = true;
                        self.match_observateur_message = response.data.message;
                        self.observateur = response.data.observateur;
                    }else{
                        self.match_observateur = false;
                        self.match_observateur_message = '';
                        self.observateur = [];
                    }
                })
                .catch(function (error) { 
                    self.isLoading = false;
                    return self.checkError(error); 
                });

            },
            create:function(){
                let self = this;
                self.isLoading = true;
                let formData = new FormData();
                self.errors = [];

                if(self.match_observateur === true && self.associer_observateur === null){
                    self.errors.push('Le champ Associer observateur est obligatoire');
                }

                if(!self.nom){
                    self.errors.push('Le champ Nom est obligatoire');
                }

                if(!self.prenom){
                    self.errors.push('Le champ Prénom est obligatoire');
                }

                if(!self.email){
                    self.errors.push('Le champ E-mail est obligatoire');
                }

                if(!self.role_selected.id || self.role_selected.id == 0){
                    self.errors.push('Le champ Rôle est obligatoire');
                }

                if(self.role_selected && self.role_selected.nom_interne == 'gestionnaire' && self.departement.length == 0){
                    self.errors.push('Le champ Département est obligatoire avec le rôle Gestionnaire');
                }

                if(self.role_selected && self.role_selected.nom_interne == 'administrateur' && self.departement.length == 0){
                    self.errors.push('Le champ Département est obligatoire avec le rôle Gestionnaire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    self.isLoading = false;
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');
                formData.append('prenom', self.prenom ? self.prenom : '');
                formData.append('email', self.email ? self.email : '');
                formData.append('role_id', self.role_selected && self.role_selected.id ? self.role_selected.id : '');
                formData.append('groupe_id', self.objectSize(self.groupe_selected) > 0 && self.groupe_selected.id ? self.groupe_selected.id : '');

                let observateur_id = '';
                if(self.match_observateur === true && self.associer_observateur === true && self.objectSize(self.observateur) > 0 && self.observateur.id){
                    observateur_id = self.observateur.id;
                }

                formData.append('observateur_id', observateur_id);


                self.departement.forEach(function(dep){
                    formData.append('departements[]', dep.id);
                });

                axios.post(base_url + '/utilisateurs', formData, { 
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
                        self.isLoading = false;
                        self.showSuccess([response.data.message]);
                        self.$router.push({path: '/utilisateurs'});
                    }
                })
                .catch(function (error) { 
                    self.isLoading = false;
                    return self.checkError(error); 
                });
            },
            detectMobile: function(event){
                this.isMobile = event;
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/utilisateurs/create')
            .then(function (resp) {
                self.fonctions = resp.data.fonctions;
                self.roles = resp.data.roles;
                self.departements = resp.data.departements;
                self.groupes = resp.data.groupes;
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
