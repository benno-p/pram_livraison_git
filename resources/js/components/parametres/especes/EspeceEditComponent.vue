<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="card col-12 col-md-12 p-0 position-relative">
                    <div class="card-header">
                        Midifer une espèce
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
                                <label for="nom" class="col-12 col-md-2 col-form-label">CD nom<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <input type="number" class="form-control mb-2" v-model="cdnom">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="nom" class="col-12 col-md-2 col-form-label">Taxon<span class="text-danger">*</span></label>
                                <div class="col-12 col-md-10">
                                    <multiselect 
                                    v-model="taxon" 
                                    :options="taxons"  
                                    placeholder="Sélection" 
                                    label="nom" 
                                    track-by="id"
                                    selectLabel=""
                                    deselectLabel=""
                                    selectedLabel="Selectionner"
                                    >
                                    </multiselect>
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
                cdnom: '',
                errors: [],
                taxons: [],
                taxon: [],
                espece: [],
                espece_type: '',
                url: '',
                espece_taxon_relation: '',
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

                if(!self.cdnom){
                    self.errors.push('Le champ CD Nom est obligatoire');
                }


                if(!self.taxon || self.taxon.length == 0){
                    self.errors.push('Le champ Taxon est obligatoire');
                }

                if(self.errors.length){
                    self.showErrors(self.errors);
                    return false;
                }

                formData.append('nom', self.nom ? self.nom : '');
                formData.append('cdnom', self.cdnom ? self.cdnom : '');
                formData.append('taxon_id', self.taxon && self.taxon.id ? self.taxon.id : '');


                formData.append('_method', 'PUT');
                axios.post(base_url + '/'+ self.url +'/' + self.espece.id, formData, { 
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
            const self = this;
            self.isLoading = true;

            if(self.$route.name == 'faunes.edit'){
                self.espece_type= 'Faune';
                self.url = 'faunes';
                self.espece_taxon_relation = 'tax_faune_groupe_id';
            }else if(self.$route.name == 'flores.edit'){
                self.espece_type = 'Flore';
                self.url = 'flores';
                self.espece_taxon_relation = 'tax_flore_groupe_id';
            }else if(self.$route.name == 'eee_faunes.edit'){
                self.espece_type = 'EEE Faune';
                self.url = 'eee_faunes';
                self.espece_taxon_relation = 'tax_eee_faune_groupe_id';
            }else if(self.$route.name == 'eee_flores.edit'){
                self.espece_type = 'EEE Flore';
                self.url = 'eee_flores';
                self.espece_taxon_relation = 'tax_eee_flore_groupe_id';
            }

            axios.get(base_url + '/'+ self.url +'/'+ this.$route.params.id + '/edit')
            .then(function (resp) {
                self.espece = resp.data.espece;
                self.nom = self.espece.nom;
                self.cdnom = self.espece.cdnom;
                self.taxons = resp.data.taxons;
                self.taxon = self.taxons.find(t => t.id == self.espece[self.espece_taxon_relation]);
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });



        },
    };
</script>
