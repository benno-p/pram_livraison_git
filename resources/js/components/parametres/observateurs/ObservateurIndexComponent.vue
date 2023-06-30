<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="d-flex">
                    <router-link :to="'/observateurs/create'" class="btn btn-primary ml-0 mr-4">
                        <i class="fas fa-plus-circle"></i> Créer un observateur
                    </router-link>

                    <router-link to="/parametres" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour aux paramètres
                    </router-link>
                </div>

                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "observateurs"
                        v-bind:allDataProps = "all_data.observateurs"
                        v-bind:columnsProps = "searchColumns"
                        v-bind:placeholderProps = "'Rechercher'"
                        v-on:searchBarResults = "searchBarResults"
                        ></searchBarComponent>

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
                                        <th>
                                            <tableSortButton 
                                            v-bind:keyProps="'organisme'" 
                                            v-bind:nameProps="'Organisme'" 
                                            v-bind:currentSortProps="currentSort"
                                            v-bind:currentSortDirProps="currentSortDir"
                                            v-on:currentSortDir = "checkCurrentSortDir"
                                            v-on:currentSort = "checkCurrentSort"
                                            ></tableSortButton>
                                        </th>
                                        <th style="width: 10%;" class="text-center">Modifier</th>
                                        <th style="width: 10%;" class="text-center" v-if="!isMobile">Supprimer</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="observateur in displayedDonnees">
                                        <td>{{observateur.nom}}</td>
                                        <td>{{observateur.prenom}}</td>
                                        <td>{{observateur.organisme}}</td>
                                        <td class="text-center">
                                            <router-link :to="'/observateurs/edit/' + observateur.id" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </router-link>
                                        </td>
                                        <td class="text-center" v-if="!isMobile">
                                            <delete-parametre-data-component
                                            :idProps = "observateur.id"
                                            :modelsProps = "observateurs"
                                            :confirmTextProps = "'Voulez-vous vraiment supprimer cet observateur ?'"
                                            :modelNameProps = "$route.name"
                                            :urlProps = "$route.name"
                                            v-on:deleteData = "deleteData"
                                            >
                                            </delete-parametre-data-component>
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
                isLoading: true,
                fullPage: false,
                currentSort: 'nom',
                currentSortDir: 'asc',
                searchColumns: ['nom'],
                pages: [],
                page: 1,
                perPage: 10,
                windowWidth: window.innerWidth,
                isMobile: false,
                all_data: [],
                observateurs: [],
                observateur_type: '',
            }
        },
        watch: {
            observateurs: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.observateurs);
                });
            },
        },
        methods: {
            deleteData: function(event){
                let observateurs_index_all_data = this.all_data.observateurs.findIndex(g => g.id == event);
                if(observateurs_index_all_data >= 0){
                    this.all_data.observateurs.splice(observateurs_index_all_data, 1);
                    this.observateurs = this.all_data.observateurs;
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
                this.observateurs = event;
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
                return this.sort(this.observateurs);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/observateurs')
            .then(function (resp) {
                self.all_data = resp.data;
                self.observateurs = resp.data.observateurs;
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
