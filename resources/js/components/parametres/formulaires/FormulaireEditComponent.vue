<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Autoriser l'enregistrement ou la modification d'un champ à un/plusieurs groupe(s)
                    </div>
                    <div class="card-body">
                        <form method="POST" v-on:submit.prevent="create()" v-if="!isLoading">
                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Nom du champ</label>
                                <div class="col-12 col-md-10">
                                    <input type="text" class="form-control mb-2" :value="formulaire.nom" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Autorisé à<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                        v-model="groupes_selected"
                                        :options="groupes" 
                                        track-by="id" 
                                        label="nom"
                                        placeholder="Sélectionner un ou plusieurs groupe(s)"
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

                                <router-link :to="'/formulaires'" class="btn btn-light">
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
                formulaire: [],
                groupes: [],
                groupes_selected: [],
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

                self.groupes_selected.forEach(function(groupe){
                    formData.append('groupes[]', groupe && groupe.id ? groupe.id : null);
                });

                formData.append('_method', 'PUT');
                axios.post(base_url + '/formulaires/' + self.formulaire.id, formData, { 
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
                        return self.$router.push({path: '/formulaires'});
                    }
                })
                .catch(function (error) { 
                    return self.checkError(error); 
                });
            },
        },
        mounted(){
            const self = this;
            axios.get(base_url + '/formulaires/'+ this.$route.params.id + '/edit')
            .then(function (resp) {
                self.isLoading = true;
                self.formulaire = resp.data.formulaire;
                self.groupes = resp.data.groupes;

                if(self.formulaire.groupes){
                    self.formulaire.groupes.forEach(function(groupe){
                        self.groupes_selected.push(groupe);
                    })
                }   

                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        }
    };
</script>
