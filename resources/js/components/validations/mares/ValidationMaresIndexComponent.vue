<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "mares"
                        v-bind:allDataProps = "all_data.mares"
                        v-bind:columnsProps = "searchColumns"
                        v-bind:placeholderProps = "'Rechercher par id, nom, departement, interco ou commune'"
                        v-on:searchBarResults = "searchBarResults"
                        ></searchBarComponent>

                        <div class="ml-auto">
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>
                    </div>

                    <div class="card col-12 col-md-12 p-0 position-relative" style="align-items: center;">
<!--                         <div class="p-4">
                            <h2>Le système de validation est en maintenance, merci de revenir plus tard</h2>
                        </div> -->
                        <template>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <tableSortButton 
                                                v-bind:keyProps="'id'" 
                                                v-bind:nameProps="'Identifiant'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th>
                                                <tableSortButton 
                                                v-bind:keyProps="'nom'" 
                                                v-bind:nameProps="'Source de la donnée'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th class="text-center">
                                                <tableSortButton 
                                                v-bind:keyProps="'departement_code'" 
                                                v-bind:nameProps="'Departement'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th>
                                                <tableSortButton 
                                                v-bind:keyProps="'intercommunalite'" 
                                                v-bind:nameProps="'Intercommunalite'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th>
                                                <tableSortButton 
                                                v-bind:keyProps="'commune'" 
                                                v-bind:nameProps="'Commune'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th style="width: 10%;" class="text-center">Saisie par</th>
                                            <th style="width: 10%;" class="text-center">Observée par</th>
                                            <th style="width: 10%;" class="text-center">Statut</th>
                                            <th style="width: 10%;" class="text-center">Message</th>
                                            <th style="width: 10%;" class="text-center">Modifier</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="mare in displayedDonnees">
                                            <td>{{mare.id}}</td>
                                            <td>{{cutLongString(mare.nom, 30)}}</td>
                                            <td class="text-center">{{mare.departement_code}}</td>
                                            <td>{{mare.intercommunalite}}</td>
                                            <td>{{mare.commune}}</td>
                                            <td v-if="!isMobile" class="text-center">{{mare.utilisateur_id ? mare.utilisateur.nom+' '+mare.utilisateur.prenom : ''}}</td>
                                            <td v-if="!isMobile" class="text-center">{{mare.observateur_id ? mare.observateur.nom+' '+mare.observateur.prenom : ''}}</td>
                                            <td class="text-center">
                                                <span v-if="mare.statut && mare.statut.nom_interne === 'valider'">
                                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                                </span>
                                                <span v-else-if="mare.statut && mare.statut.nom_interne === 'refuser'">
                                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                                </span>
                                                <span v-else-if="mare.statut && mare.statut.nom_interne === 'en_attente_de_validation'">
                                                    <i class="fas fa-solid fa-clock text-orange fa-lg"></i>
                                                </span>
                                                <span v-else>
                                                    N/C
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button :class="mare.commentaires.length > 0 ? 'btn btn-info' : 'btn btn-secondary'" @click.prevent="showCommentaires(mare)">
                                                    <i class="fas fa-comments text-white mr-0"></i>
                                                    <template v-if="checkIfCommentaire(mare)">
                                                        <span class="badge badge-light position-absolute text-white bg-danger">{{mare.commentaires_validateurs_non_lus}}</span>
                                                    </template>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <router-link :to="'/validation_mares/edit/' + mare.id" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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

        <router-view
            name="second"
            :mare="mare_selected"
            :statuts="statuts"
            :type="'mare'"
            v-on:updateMare="updateMare"
        ></router-view>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isLoading: true,
                fullPage: false,
                currentSort: 'id',
                currentSortDir: 'asc',
                searchColumns: ['id', 'nom', 'departement_code', 'intercommunalite', 'commune'],
                pages: [],
                page: 1,
                perPage: 10,
                windowWidth: window.innerWidth,
                isMobile: false,
                all_data: [],
                mares: [],
                mare_selected: [],
                statuts: [],
            }
        },
        watch: {
            mares: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.mares);
                });
            },
        },
        methods: {
            updateMare: function(mare){
                let index = this.mares.findIndex(m => m.id === mare.id);

                if(index >= 0){
                    this.mares.splice(index, 1, mare);
                }
            },
            showCommentaires: function(mare){
                this.mare_selected = mare;
                this.$router.push({path: '/validation_mares/commentaires'});
            },
            checkIfCommentaire: function(mare){
                if(mare.commentaires_validateurs_non_lus && mare.commentaires_validateurs_non_lus > 0){
                    return true;
                }
                return false;
            },
            deleteData: function(event){
                let taxons_index_all_data = this.all_data.taxons.findIndex(m => m.id == event);
                if(taxons_index_all_data >= 0){
                    this.all_data.taxons.splice(taxons_index_all_data, 1);
                    this.taxons = this.all_data.taxons;
                    this.$refs.searchBarComponent.searchBar = '';
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
                this.page = 1;
                this.mares = event;
            },
            sort: function(data) {
                let currentSort = this.currentSort;
                let currentSortDir = this.currentSortDir;
                let self = this;
                data.sort(function(a, b) { 
                    let modifier = 1;
                    if(self.currentSortDir === 'desc') modifier = -1;
                    return a[currentSort]> b[currentSort] ? 1*modifier : -1*modifier;
                });
                let from = (this.page * this.perPage) - this.perPage;
                let to = (this.page * this.perPage);
                return data.slice(from, to);
            },
        },
        computed: {
            displayedDonnees: function() {
                return this.sort(this.mares);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/validation_mares')
            .then(function (resp) {
                self.all_data = resp.data;
                self.mares = resp.data.mares;
                self.statuts = resp.data.statuts;
                self.isLoading = false;             
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
