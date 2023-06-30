<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <searchBarComponent
                        ref="searchBarComponent"
                        v-bind:dataProps = "fiches"
                        v-bind:allDataProps = "all_data.fiches"
                        v-bind:columnsProps = "searchColumns"
                        v-bind:placeholderProps = "'Rechercher par id fiche, departement, intercommunalite ou commune'"
                        v-on:searchBarResults = "searchBarResults"
                        ></searchBarComponent>

                        <div class="ml-auto">
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>
                    </div>

                    <p class="">{{fiches.length}}</p>

                    <div class="card col-12 col-md-12 p-0 position-relative" style="align-items: center;">
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
                                            <th class="text-center">Id mare</th>
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
                                                v-bind:keyProps="'departement'" 
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
                                                v-bind:keyProps="'commune.nom'" 
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
                                        <tr v-for="fiche in displayedDonnees">
                                            <td>{{fiche.id}}</td>
                                            <td class="text-center">{{fiche.mare.id}}</td>
                                            <td>{{cutLongString(fiche.mare.nom, 30)}}</td>
                                            <td class="text-center">{{fiche.departement}}</td>
                                            <td>{{fiche.intercommunalite}}</td>
                                            <td>{{fiche.commune}}</td>
                                            <td v-if="!isMobile" class="text-center">{{fiche.utilisateur_id ? fiche.utilisateur.nom+' '+fiche.utilisateur.prenom : ''}}</td>
                                            <td v-if="!isMobile" class="text-center">{{fiche.observateur_id ? fiche.observateur.nom+' '+fiche.observateur.prenom : ''}}</td>
                                            <td class="text-center">
                                                <span v-if="fiche.statut && fiche.statut.nom_interne === 'valider'">
                                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                                </span>
                                                <span v-else-if="fiche.statut && fiche.statut.nom_interne === 'refuser'">
                                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                                </span>
                                                <span v-else-if="fiche.statut && fiche.statut.nom_interne === 'en_attente_de_validation'">
                                                    <i class="fas fa-solid fa-clock text-orange fa-lg"></i>
                                                </span>
                                                <span v-else>
                                                    N/C
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button :class="fiche.commentaires.length > 0 ? 'btn btn-info' : 'btn btn-secondary'" @click.prevent="showCommentaires(fiche)">
                                                    <i class="fas fa-comments text-white mr-0"></i>
                                                    <template v-if="checkIfCommentaire(fiche)">
                                                        <span class="badge badge-light position-absolute text-white bg-danger">{{fiche.commentaires_validateurs_non_lus}}</span>
                                                    </template>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <router-link :to="'/validation_fiches/edit/' + fiche.id" class="btn btn-primary">
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
            :fiche="fiche_selected"
            :statuts="statuts"
            :type="'fiche'"
            v-on:updateFiche="updateFiche"
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
                searchColumns: ['id', 'departement', 'intercommunalite', 'commune'],
                pages: [],
                page: 1,
                perPage: 10,
                windowWidth: window.innerWidth,
                isMobile: false,
                all_data: [],
                fiches: [],
                fiche_selected: [],
                statuts: [],
            }
        },
        watch: {
            fiches: function() {
                this.$nextTick(function(){
                    this.$refs.pagination.setPages(this.perPage,this.fiches);
                });
            },
        },
        methods: {
            updateFiche: function(fiche){
                let index = this.fiches.findIndex(f => f.id === fiche.id);

                if(index >= 0){
                    this.fiches.splice(index, 1, fiche);
                }
            },
            showCommentaires: function(fiche){
                this.fiche_selected = fiche;
                this.$router.push({path: '/validation_fiches/commentaires'});
            },
            checkIfCommentaire: function(fiche){
                if(fiche.commentaires_validateurs_non_lus && fiche.commentaires_validateurs_non_lus > 0){
                    return true;
                }
                return false;
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
                this.fiches = event;
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
                return this.sort(this.fiches);
            },
        },
        mounted:function(){
            const self = this;
            self.isLoading = true;
            axios.get(base_url + '/validation_fiches')
            .then(function (resp) {
                self.all_data = resp.data;
                self.fiches = resp.data.fiches;
                self.statuts = resp.data.statuts;

                self.fiches.forEach(function(fiche){
                    fiche.departement = fiche.mare && fiche.mare.departement_code ? fiche.mare.departement_code : '';
                    fiche.intercommunalite = fiche.mare && fiche.mare.intercommunalite && fiche.mare.intercommunalite.raison_soc ? fiche.mare.intercommunalite.raison_soc : '';
                    fiche.commune = fiche.mare && fiche.mare.commune && fiche.mare.commune.nom ? fiche.mare.commune.nom : '';
                })

                self.isLoading = false;             
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
