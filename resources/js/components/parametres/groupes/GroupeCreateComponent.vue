<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Créer un nouveau groupe
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
                                <label for="nom" class="col-12 col-md-2 col-form-label">Relié à<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                        v-model="types_selected"
                                        :options="types" 
                                        deselectLabel="" 
                                        track-by="nom_interne" 
                                        label="nom"
                                        placeholder="Types"
                                        selectLabel=""
                                        :searchable="true" 
                                        :allow-empty="false"
                                        @input="changeType()"
                                        >
                                    </multiselect>
                                </div>
                            </div>

                            <div class="form-group row" v-if="objectSize(types_selected) > 0 && types_selected.nom_interne === 'departements'">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Départements<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                        v-model="departements_selected"
                                        :options="departements" 
                                        deselectLabel="" 
                                        track-by="id" 
                                        label="nom"
                                        placeholder="Sélectionner un ou plusieurs départements"
                                        selectLabel=""
                                        :searchable="true" 
                                        :allow-empty="true"
                                        :multiple="true" 
                                        :close-on-select="true" 
                                        :clear-on-select="true" 
                                        :preserve-search="true" 
                                        >
                                    </multiselect>
                                </div>
                            </div>

                            <div class="form-group row" v-if="objectSize(types_selected) > 0 && types_selected.nom_interne === 'intercommunalites'">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Intercommunalités<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                        v-model="intercommunalites_selected"
                                        :options="intercommunalites" 
                                        deselectLabel="" 
                                        track-by="id" 
                                        label="raison_soc"
                                        placeholder="Sélectionner une ou plusieurs intercommunalités"
                                        selectLabel=""
                                        :searchable="true" 
                                        :allow-empty="true"
                                        :multiple="true" 
                                        :close-on-select="true" 
                                        :clear-on-select="true" 
                                        :preserve-search="true" 
                                        >
                                    </multiselect>
                                </div>
                            </div>

                            <div class="form-group row" v-if="objectSize(types_selected) > 0 && types_selected.nom_interne === 'communes'">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Communes<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                        v-model="communes_selected"
                                        :options="communes" 
                                        deselectLabel="" 
                                        track-by="id" 
                                        label="label"
                                        placeholder="Sélectionner une ou plusieurs communes"
                                        selectLabel=""
                                        :searchable="true" 
                                        :allow-empty="true"
                                        :multiple="true" 
                                        :close-on-select="true" 
                                        :clear-on-select="true" 
                                        :preserve-search="true" 
                                        >
                                    </multiselect>
                                </div>
                            </div>

                            <div class="col-12 mt-4 p-0">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>

                                <router-link :to="'/groupes'" class="btn btn-light">
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
                departements: [],
                departements_selected: [],
                intercommunalites: [],
                intercommunalites_selected: [],
                communes: [],
                communes_selected: [],
                types: [],
                types_selected: [],
            }
        },
        methods: {
            changeType: function(){
                this.departements_selected = [];
                this.intercommunalites_selected = [];
                this.communes_selected = [];
            },
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

                if(self.objectSize(self.types_selected) === 0){
                    self.errors.push("Le champ relié à est obligatoire");
                }

                if(self.objectSize(self.types_selected) > 0 && self.types_selected.nom_interne === 'departements' && self.objectSize(self.departements_selected) === 0){
                    self.errors.push("Le champ Départements est obligatoire");
                }

                if(self.objectSize(self.types_selected) > 0 && self.types_selected.nom_interne === 'intercommunalites' && self.objectSize(self.intercommunalites_selected) === 0){
                    self.errors.push("Le champ Intercommunalités est obligatoire");
                }

                if(self.objectSize(self.types_selected) > 0 && self.types_selected.nom_interne === 'communes' && self.objectSize(self.communes_selected) === 0){
                    self.errors.push("Le champ Communes est obligatoire");
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');

                self.departements_selected.forEach(function(dep){
                    formData.append('departements[]', dep && dep.id ? dep.id : null);
                });
                self.intercommunalites_selected.forEach(function(interco){
                    formData.append('intercommunalites[]', interco && interco.id ? interco.id : null);
                });
                self.communes_selected.forEach(function(com){
                    formData.append('communes[]', com && com.id ? com.id : null);
                });

                axios.post(base_url + '/groupes', formData, { 
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
                        return self.$router.push({path: '/groupes'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
        },
        mounted(){
            const self = this;
            axios.get(base_url + '/groupes/create')
            .then(function (resp) {
                self.isLoading = true;
                self.departements = resp.data.departements;
                self.intercommunalites = resp.data.intercommunalites;
                self.communes = resp.data.communes;
                self.types = resp.data.types;
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        }
    };
</script>
