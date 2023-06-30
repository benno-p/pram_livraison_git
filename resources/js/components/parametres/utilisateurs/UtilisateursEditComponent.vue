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
                                                <input type="checkbox" class="custom-control-input" :id="'checkbox_dep' + dep.id" :value="dep.id" v-model="departement">
                                                <label class="custom-control-label" :for="'checkbox_dep' + dep.id">{{dep.nom}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label for="nom" class="col-12 col-md-2 col-form-label">Actif<span class="text-danger">*</span></label>
                                    <div class="col-12 col-md-10">
                                        <select class="form-control mb-2" v-model="actif">
                                            <option value="0">Non</option>
                                            <option value="1">Oui</option>
                                        </select>
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
                actif: 0,
                roles: [],
                role_selected: [],
                utilisateur: [],
                departements: [],
                departement: [],
                groupes: [],
                groupe_selected: [],
            }
        },
        methods: {
            create:function(){
                let self = this;
                self.isLoading = true;
                let formData = new FormData();
                self.errors = [];

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

                if(!self.actif){
                    self.errors.push('Le champ Actif est obligatoire');
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
                formData.append('actif', self.actif == '1' ? 1 : 0);
                formData.append('groupe_id', self.objectSize(self.groupe_selected) > 0 && self.groupe_selected.id ? self.groupe_selected.id : '');

                self.departement.forEach(function(dep){
                    formData.append('departements[]', dep);
                });

                formData.append('_method', 'PUT');
                axios.post(base_url + '/utilisateurs/' + self.utilisateur.id, formData, {   
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
            axios.get(base_url + '/utilisateurs/'+ this.$route.params.id + '/edit')
            .then(function (resp) {
                self.departements = resp.data.departements;
                self.roles = resp.data.roles;
                self.utilisateur = resp.data.utilisateur;
                self.groupes = resp.data.groupes;

                if(self.utilisateur){
                    self.nom = self.utilisateur.nom;
                    self.prenom = self.utilisateur.prenom;
                    self.email = self.utilisateur.email;
                    self.actif = self.utilisateur.actif;
                    self.role_selected = self.roles.find(r => r.id == self.utilisateur.role_id);

                    if(self.utilisateur.groupe_id){
                        self.groupe_selected = self.groupes.find(g => g.id === self.utilisateur.groupe_id);
                    }

                    if(self.utilisateur.departements){
                        self.utilisateur.departements.forEach(function(dep){
                            self.departement.push(dep.id);
                        })
                    }
                }

                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
