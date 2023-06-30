<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4">
                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100">
                        <div class="col-3 pl-0" v-if="objectSize(types) > 0">
                            <multiselect
                            v-model="types_selected"
                            :options="types" 
                            deselectLabel="Cliquez pour enlever" 
                            track-by="id" 
                            label="nom" 
                            placeholder="Sélectionner un groupe"
                            selectLabel=""
                            :searchable="false" 
                            :allow-empty="false"
                            @input="filter()"
                            >
                            </multiselect>
                        </div>

                        <div class="col-3 pl-0">
                            <multiselect
                            v-model="statut_selected"
                            :options="statuts" 
                            deselectLabel="Cliquez pour enlever" 
                            track-by="id" 
                            label="nom" 
                            placeholder="Sélectionner un statut"
                            selectLabel=""
                            :searchable="false" 
                            :allow-empty="true"
                            @input="filter()"
                            >
                            </multiselect>
                        </div>

                        <div class="ml-auto">
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>
                    </div>

                    <p class="m-0 p-1">{{all_data && all_data.total ? all_data.total : ''}} résultat(s) pour votre recherche</p>

                    <div class="card col-12 col-md-12 p-0 position-relative mt-4" style="align-items: center;">
                        <template v-if="fiches.length > 0">
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
                                            <th v-if="!isMobile">
                                                <tableSortButton
                                                v-bind:keyProps="'mares.id'" 
                                                v-bind:nameProps="'Identifiant mare'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th class="text-center">
                                                <tableSortButton 
                                                v-bind:keyProps="'mares.departement_code'" 
                                                v-bind:nameProps="'Departement'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th v-if="!isMobile">
                                                <tableSortButton
                                                v-bind:keyProps="'intercommunalites.raison_soc'" 
                                                v-bind:nameProps="'Intercommunalite'" 
                                                v-bind:currentSortProps="currentSort"
                                                v-bind:currentSortDirProps="currentSortDir"
                                                v-on:currentSortDir = "checkCurrentSortDir"
                                                v-on:currentSort = "checkCurrentSort"
                                                ></tableSortButton>
                                            </th>
                                            <th v-if="!isMobile">
                                                <tableSortButton
                                                v-bind:keyProps="'communes.nom'" 
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
                                            <th style="width: 10%;" class="text-center" v-if="types_selected && types_selected.id === 0">Message</th>
                                            <th style="width: 10%;" class="text-center">Consulter</th>
                                            <th style="width: 10%;" class="text-center" v-if="$user.role !='utilisateur'">
                                                Supprimer
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="fiche in fiches">
                                            <td>{{fiche.id}}</td>
                                            <td v-if="!isMobile">{{fiche.mare.id}}</td>
                                            <td class="text-center">{{fiche.departement}}</td>
                                            <td v-if="!isMobile">{{fiche.intercommunalite}}</td>
                                            <td v-if="!isMobile">{{fiche.commune}}</td>
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
                                            <td class="text-center" v-if="types_selected && types_selected.id === 0">
                                                <button :class="fiche.commentaires.length > 0 ? 'btn btn-info' : 'btn btn-secondary'" @click.prevent="showCommentaires(fiche)">
                                                    <i class="fas fa-comments text-white mr-0"></i>
                                                    <template v-if="checkIfCommentaire(fiche)">
                                                        <span class="badge badge-light position-absolute text-white bg-danger">{{fiche.commentaires_utilisateurs_non_lus}}</span>
                                                    </template>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <template v-if="fiche.statut && fiche.statut.nom_interne === 'en_attente_de_validation'">
                                                    <router-link :to="'/fiches_attentes/edit/' + fiche.id" class="btn btn-primary">
                                                        <span>
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    </router-link>
                                                </template>
                                                <template v-else>
                                                    <router-link :to="'/mes_fiches/consultation/' + fiche.mare_id" class="btn btn-primary">
                                                        <!-- :to="{ name: 'mes_fiches.consultation', params: {id: fiche.mare_id, tabIndex: 4 } }" -->
                                                        <span>
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                    </router-link>
                                                </template>
                                            </td>
                                            <td class="text-center" v-if="$user.role !='utilisateur'">
                                                <delete-parametre-data-component
                                                :idProps = "fiche.id"
                                                :modelsProps = "fiches"
                                                :confirmTextProps = "'Voulez-vous vraiment supprimer cette fiche ? Cette action est irréversible'"
                                                :modelNameProps = "$route.name"
                                                :urlProps = "'mes_fiches'"
                                                v-on:deleteData = "deleteData"
                                                >
                                                </delete-parametre-data-component>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>

                        <template v-else>
                            <p class="p-4">Aucune fiche en attente</p>
                        </template>

                        <l-pagination
                        :pageProps="page"
                        :pagesProps="pages"
                        :perPageProps="perPage"
                        :lightPaginationProps="true"
                        v-on:changePage="changePage"
                        >
                        </l-pagination>
                    </div>
                </div>
            </div>
        </b-container>

        <router-view
            name="second"
            :fiche="fiche_selected"
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
                pages: [],
                page: 1,
                perPage: 10,
                windowWidth: window.innerWidth,
                isMobile: false,
                searchColumns: ['nom'],
                all_data: [],
                fiches: [],
                fiche_selected: [],
                base_url: base_url,
                types: [],
                types_selected: [],
                statuts: [],
                statut_selected: [],
            }
        },
        methods: {
            deleteData: function(event){
                return this.filter();
            },
            filter: function(){
                let self = this;
                let formData = new FormData();
                let statut_id = '';
                let route = self.base_url + '/mes_fiches/toutes_mes_fiches';
                self.isLoading = true;

                if(self.objectSize(self.types_selected) > 0 && self.types_selected.id == 0){
                    route = self.base_url + '/mes_fiches/toutes_mes_fiches?page='+this.page;
                }else if(self.objectSize(self.types_selected) > 0 && self.types_selected.id != 0){
                    route = self.base_url + '/mes_fiches/fiches_du_groupe?page='+this.page;
                }

                if(this.objectSize(this.statut_selected) > 0 && this.statut_selected.id){
                    statut_id = this.statut_selected.id;
                }

                formData.append('statut_id', statut_id);
                formData.append('per_page', self.perPage);
                formData.append('currentSort', self.currentSort);
                formData.append('currentSortDir', self.currentSortDir);

                axios.post(route, formData, { 
                    _method: 'POST',
                })
                .then(function (resp) {
                    self.all_data = resp.data.fiches;
                    self.fiches = resp.data.fiches.data;

                    self.fiches.forEach(function(fiche){
                        fiche.departement = fiche.mare && fiche.mare.departement_code ? fiche.mare.departement_code : '';
                        fiche.intercommunalite = fiche.mare && fiche.mare.intercommunalite && fiche.mare.intercommunalite.raison_soc ? fiche.mare.intercommunalite.raison_soc : '';
                        fiche.commune = fiche.mare && fiche.mare.commune && fiche.mare.commune.nom ? fiche.mare.commune.nom : '';
                    })

                    self.page = resp.data.fiches.current_page;
                    self.pages = resp.data.fiches.last_page;
                    self.perPage = resp.data.fiches.per_page;
                    self.isLoading = false;
                })
                .catch(function (resp) {
                    return self.checkError(resp);
                });
            },
            updateFiche: function(fiche){
                let index = this.fiches.findIndex(f => f.id === fiche.id);

                if(index >= 0){
                    this.fiches.splice(index, 1, fiche);
                }
            },
            showCommentaires: function(fiche){
                this.fiche_selected = fiche;
                this.$router.push({path: '/fiches_attentes/commentaires'});
            },
            checkIfCommentaire: function(fiche){
                if(fiche.commentaires_utilisateurs_non_lus && fiche.commentaires_utilisateurs_non_lus > 0){
                    return true;
                }
                return false;
            },
            changePage: function(val){
                this.page = val;
                this.filter();
            },
            checkPages: function(event){
                this.perPage = event;
                this.filter();
            },
            checkCurrentSortDir: function(event){
                this.currentSortDir = event;
                this.filter();
            },
            checkCurrentSort: function(event){
                this.currentSort = event;
                this.filter();
            },
        },
        mounted:function(){
            let self = this;
            axios.get(self.base_url + '/mes_fiches')
            .then(function (resp) {
                self.types = resp.data.types;
                self.types_selected = resp.data.types_selected;
                self.statuts = resp.data.statuts;
                self.isLoading = false;
                self.statut_selected = self.statuts.find(s => s.nom_interne === 'en_attente_de_validation');
                return self.filter();
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
