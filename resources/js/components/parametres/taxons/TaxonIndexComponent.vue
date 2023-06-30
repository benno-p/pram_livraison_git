<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="d-flex">
                    <router-link :to="'/'+$route.name+'/create'" class="btn btn-primary ml-0 mr-4">
                        <i class="fas fa-plus-circle"></i> Ajouter un Taxon
                    </router-link>

                    <router-link to="/parametres" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour aux paramètres
                    </router-link>
                </div>

                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "taxons"
                        v-bind:allDataProps = "all_data.taxons"
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
                                        <th style="width: 10%;" class="text-center">Modifier</th>
                                        <th style="width: 10%;" class="text-center" v-if="!isMobile">Supprimer</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="taxon in displayedDonnees">
                                        <td>{{taxon.nom}}</td>
                                        <td class="text-center">
                                            <router-link :to="'/'+ $route.name +'/edit/' + taxon.id" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </router-link>
                                        </td>
                                        <td class="text-center" v-if="!isMobile">
                                            <delete-parametre-data-component
                                            :idProps = "taxon.id"
                                            :modelsProps = "taxons"
                                            :confirmTextProps = "'Voulez-vous vraiment supprimer le taxon ?'"
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
                taxons: [],
                taxon_type: '',
            }
        },
        watch: {
            taxons: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.taxons);
                });
            },
        },
        methods: {
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
                this.taxons = event;
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
                return this.sort(this.taxons);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/' + self.$route.name)
            .then(function (resp) {
                self.all_data = resp.data;
                self.taxons = resp.data.taxons;
                self.isLoading = false;

                if(self.$route.name == 'taxon_faunes'){
                    self.taxon_type = 'Faune';
                }else if(self.$route.name == 'taxon_flores'){
                    self.taxon_type = 'Flore';
                }else if(self.$route.name == 'taxon_eee_faunes'){
                    self.taxon_type = 'EEE Faune';
                }else if(self.$route.name == 'taxon_eee_flores'){
                    self.taxon_type = 'EEE Flore';
                }                
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
