<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="d-flex">
                    <router-link :to="'/configuration_logo_partenaires/create'" class="btn btn-primary ml-0 mr-4">
                        <i class="fas fa-plus-circle"></i> Ajouter un partenaire
                    </router-link>

                    <router-link to="/parametres" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour aux paramètres
                    </router-link>
                </div>

                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "partenaires"
                        v-bind:allDataProps = "all_data.partenaires"
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
                                        <th style="width: 10%;" class="text-center">URL site internet</th>
                                        <th style="width: 10%;" class="text-center">Modifier</th>
                                        <th style="width: 10%;" class="text-center" v-if="!isMobile">Supprimer</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="partenaire in displayedDonnees">
                                        <td>{{partenaire.nom}}</td>
                                        <td>{{partenaire.site_url}}</td>
                                        <td class="text-center">
                                            <router-link :to="'/configuration_logo_partenaires/edit/' + partenaire.id" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </router-link>
                                        </td>
                                        <td class="text-center" v-if="!isMobile">
                                            <delete-parametre-data-component
                                            :idProps = "partenaire.id"
                                            :modelsProps = "partenaires"
                                            :confirmTextProps = "'Voulez-vous vraiment supprimer ce partenaire ?'"
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
                partenaires: [],
                partenaire_type: '',
            }
        },
        watch: {
            partenaires: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.partenaires);
                });
            },
        },
        methods: {
            deleteData: function(event){
                let partenaires_index_all_data = this.all_data.partenaires.findIndex(g => g.id == event);
                if(partenaires_index_all_data >= 0){
                    this.all_data.partenaires.splice(partenaires_index_all_data, 1);
                    this.partenaires = this.all_data.partenaires;
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
                this.partenaires = event;
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
                return this.sort(this.partenaires);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/configuration_logo_partenaires')
            .then(function (resp) {
                self.all_data = resp.data;
                self.partenaires = resp.data.partenaires;
                self.isLoading = false;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
