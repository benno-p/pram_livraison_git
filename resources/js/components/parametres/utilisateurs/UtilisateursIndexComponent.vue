<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <router-link to="/utilisateurs/create" class="btn btn-primary mr-auto">
                    <i class="fas fa-plus-circle"></i> Ajouter un utilisateur
                </router-link>

                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "utilisateurs"
                        v-bind:allDataProps = "all_data.utilisateurs"
                        v-bind:columnsProps = "searchColumns"
                        v-bind:placeholderProps = "'Nom, Prénom, Rôle'"
                        v-on:searchBarResults = "searchBarResults"
                        ></searchBarComponent>

                        <div class="col-3">
                            <multiselect
                            v-model="groupe_selected"
                            :options="groupes" 
                            deselectLabel="Cliquez pour enlever" 
                            track-by="id" 
                            label="nom" 
                            placeholder="Sélectionner un groupe"
                            selectLabel=""
                            :searchable="false" 
                            :allow-empty="true"
                            @input="filterByGroupe()"
                            >
                            </multiselect>
                        </div>

                        <div class="auto">
                            <button class="btn btn-success" @click.prevent="exportUser()">
                                <i class="fas fa-file-export"></i>
                                Export
                            </button>
                        </div>

                        <div class="ml-auto">
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>
                    </div>


                    <div class="card col-12 col-md-12 p-0 position-relative" style="align-items: center;">
                        <template>
                            <table class="table table-striped table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <tableSortButton 
                                            v-bind:keyProps="'nom'" 
                                            v-bind:nameProps="'Nom'" 
                                            v-bind:currentSortProps="currentSort"
                                            v-bind:currentSortDirProps="currentSortDir"
                                            v-on:currentSortDir = "checkCurrentSortDir"
                                            v-on:currentSort = "checkCurrentSort"
                                            ></tableSortButton>
                                        </th>
                                        <th>
                                            <tableSortButton 
                                            v-bind:keyProps="'prenom'" 
                                            v-bind:nameProps="'Prénom'" 
                                            v-bind:currentSortProps="currentSort"
                                            v-bind:currentSortDirProps="currentSortDir"
                                            v-on:currentSortDir = "checkCurrentSortDir"
                                            v-on:currentSort = "checkCurrentSort"
                                            ></tableSortButton>
                                        </th>
                                        <th v-if="!isMobile">
                                            <tableSortButton
                                            v-bind:keyProps="'role'"
                                            v-bind:nameProps="'Role'" 
                                            v-bind:currentSortProps="currentSort"
                                            v-bind:currentSortDirProps="currentSortDir"
                                            v-on:currentSortDir = "checkCurrentSortDir"
                                            v-on:currentSort = "checkCurrentSort"
                                            ></tableSortButton>
                                        </th>
                                        <th>E-mail</th>
                                        <th v-if="!isMobile">
                                            <tableSortButton
                                            v-bind:keyProps="'groupe'"
                                            v-bind:nameProps="'Groupe'" 
                                            v-bind:currentSortProps="currentSort"
                                            v-bind:currentSortDirProps="currentSortDir"
                                            v-on:currentSortDir = "checkCurrentSortDir"
                                            v-on:currentSort = "checkCurrentSort"
                                            ></tableSortButton>
                                        </th>
                                        <th style="width: 10%;" class="text-center">Modifier</th>
                                        <th style="width: 10%;" class="text-center" v-if="!isMobile">Actif</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="utilisateur in displayedDonnees">
                                        <td>{{utilisateur.nom}}</td>
                                        <td>{{utilisateur.prenom}}</td>
                                        <td v-if="!isMobile">{{utilisateur.role}}</td>
                                        <td>{{utilisateur.email}}</td>
                                        <td>{{utilisateur.groupe}}</td>
                                        <td class="text-center">
                                            <router-link :to="'/utilisateurs/edit/' + utilisateur.id" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </router-link>
                                        </td>
                                        <td class="text-center" v-if="!isMobile">
                                            <template v-if="utilisateur.actif == 1">
                                                <button class="btn btn-success" @click="activateDesactivateUser(utilisateur.id)">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button class="btn btn-danger" @click="activateDesactivateUser(utilisateur.id)">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>

                        <pagination
                        ref="pagination"
                        v-bind:pageProps = "page"
                        v-bind:perPageProps = "perPage"
                        v-on:page = "checkPage"
                        ></pagination>
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
                base_url: base_url,
                isLoading: true,
                fullPage: false,
                currentSort: 'nom',
                currentSortDir: 'asc',
                pages: [],
                page: 1,
                perPage: 10,
                windowWidth: window.innerWidth,
                isMobile: false,
                searchColumns: ['nom', 'prenom', 'role', 'groupe'],
                all_data: [],
                utilisateurs: [],
                groupes: [],
                groupe_selected: [],
            }
        },
        watch: {
            utilisateurs: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.utilisateurs);
                });
            },
        },
        methods: {
            exportUser(){
                let self = this;
                let data = {
                    utilisateurs: self.utilisateurs
                }

                self.isLoading = true;
                axios.post(self.base_url + '/exports/exports_utilisateurs', data)
                .then(function (resp) {
                    window.location.href = resp.data.url;
                    self.isLoading = false;
                })
                .catch(function (resp) {
                    self.isLoading = false;
                    return self.checkError(resp);
                });
                self.isLoading = false;
            },
            filterByGroupe: function(){
                this.utilisateurs = this.all_data.utilisateurs;
                if(this.objectSize(this.groupe_selected) > 0){
                    return this.utilisateurs = this.utilisateurs.filter(u => u.groupe === this.groupe_selected.nom);
                }
            },
            activateDesactivateUser: function(user_id){
                let self = this;
                let user = self.all_data.utilisateurs.find(u => u.id == user_id);
                let confirmation = false;

                if(user){
                    if(user.actif == 1){
                        if(confirm('Voulez-vous désativer l\'utilisateur ' + user.nom + ' ' + user.prenom + ' ?')){
                            confirmation = true;
                        }
                    }else{
                        if(confirm('Voulez-vous activer l\'utilisateur ' + user.nom + ' ' + user.prenom + ' ?')){
                            confirmation = true;
                        }
                    }

                    if(confirmation == true){
                        axios.delete(base_url + '/utilisateurs/' + user.id, {params: {'id': user.id}})
                        .then((response) => {
                            let utilisateur_index_utilisateurs = self.utilisateurs.findIndex(u => u.id == user.id);
                            let utilisateur_index_all_data_utilisateurs = self.all_data.utilisateurs.findIndex(u => u.id == user.id);
                            if(response.data.error == false){
                                if(utilisateur_index_utilisateurs >= 0){
                                    self.utilisateurs.splice(utilisateur_index_utilisateurs, 1, response.data.utilisateur);
                                }

                                if(utilisateur_index_all_data_utilisateurs >= 0){
                                    self.all_data.utilisateurs.splice(utilisateur_index_all_data_utilisateurs, 1, response.data.utilisateur);    
                                }

                                return self.showSuccess(response.data.message);
                            }else{
                                return self.showErrors(response.data.message);
                            }
                        }, (error) => {
                            return self.checkError(error);
                        });
                    }

                    return false;
                }
            },
            checkPage: function(event){
                this.page = event;
            },
            checkCurrentSortDir: function(event){
                this.currentSortDir = event;
            },
            checkCurrentSort: function(event){
                this.currentSort = event;
            },
            checkPages: function(event){
                this.page = 1;
                this.perPage = event;
            },
            detectMobile: function(event){
                this.isMobile = event;
            },
            searchBarResults: function(event){
                this.groupe_selected = [];
                this.page = 1;
                this.utilisateurs = event;
            },
            sort: function(data) {
                let currentSort = this.currentSort;
                let currentSortDir = this.currentSortDir;
                let self = this;
                data.sort(function(a, b) { 
                    let modifier = 1;
                    if(self.currentSortDir === 'desc') modifier = -1;
                    return a[currentSort].toLowerCase() > b[currentSort].toLowerCase() ? 1*modifier : -1*modifier;
                });
                let from = (this.page * this.perPage) - this.perPage;
                let to = (this.page * this.perPage);
                return data.slice(from, to);
            },
        },
        computed: {
            displayedDonnees: function() {
                return this.sort(this.utilisateurs);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/utilisateurs')
            .then(function (resp) {
                self.all_data = resp.data;
                self.utilisateurs = resp.data.utilisateurs;
                self.groupes = resp.data.groupes;
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
