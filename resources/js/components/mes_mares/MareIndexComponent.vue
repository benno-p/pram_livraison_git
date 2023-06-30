<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <router-view
            name="second"
            :mare="mare_selected"
            :type="'mare'"
            v-on:updateMare="updateMare"
        ></router-view>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
            <div class="card p-4" v-if="!first_loading">
                <div class="mt-4">
                    <div class="d-flex flex-wrap w-100 mb-2">
                        <div class="col-12 col-md-3 pl-0" v-if="objectSize(types) > 0">
                            <label class="col-12 pl-0">Type</label>
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

                        <div class="col-12 col-md-2 pl-0">
                            <label class="col-12 pl-0">Statut</label>
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

                        <div class="col-12 col-md-3 mb-4" v-if="checkIfUserCanChangeInput('null')">
                            <label class="col-12 pl-0">
                                Recherche par identifiant de mare <i class="fas fa-info-circle" v-tooltip="'Si vous ne trouvez pas une mare par la recherche rapide, pensez à modifier le type dans les filtres et sélectionner votre groupe ou \'Mes mares\'.'"></i>
                            </label>
                            <autocomplete
                                ref="autocomplete"
                                placeholder="Identifiant de mare"
                                :source="base_url + '/recherche/search_mare?q='"
                                input-class="form-control"
                                results-property="mares"
                                results-display="label"
                                @selected="selectedMare"
                                @clear="clear">
                            </autocomplete>
                        </div>

                        <div class="col-12 col-md-2 mb-4" v-if="checkIfUserCanChangeInput('code_ofb')">
                            <label class="col-12 pl-0">
                                Recherche par code OFB <i class="fas fa-info-circle" v-tooltip="'Si vous ne trouvez pas une mare par la recherche rapide, pensez à modifier le type dans les filtres et sélectionner votre groupe ou \'Mes mares\'.'"></i>
                            </label>
                            <autocomplete
                                ref="autocomplete"
                                placeholder="Code OFB"
                                :source="base_url + '/recherche/search_mare?type=code_ofb&q='"
                                input-class="form-control"
                                results-property="mares"
                                results-display="label"
                                @selected="selectedMare"
                                @clear="clear">
                            </autocomplete>
                        </div>

                        <div class="ml-auto" v-if="objectSize(mares) > 0">
                            <label class="col-12 pl-0">&nbsp;</label>
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>
                    </div>

                    <p class="m-0 p-1">{{all_data && all_data.total ? all_data.total : ''}} résultat(s) pour votre recherche</p>

                    <div class="card col-12 col-md-12 p-0 position-relative align-items-center">
                        <template v-if="objectSize(mares) > 0">
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
                                            <th style="width: 10%;" class="text-center">
                                                <span v-if="edit_url == '/mes_mares/consultation/'">
                                                    Consulter
                                                </span>
                                                <span v-else>
                                                    Modifier
                                                </span>
                                            </th>

                                            <th style="width: 10%;" class="text-center" v-if="$user.role !='utilisateur'">
                                                Supprimer
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="mare in mares">
                                            <td>{{mare.id}}</td>
                                            <td v-if="!isMobile">{{cutLongString(mare.nom, 30)}}</td>
                                            <td class="text-center">{{mare.departement_code}}</td>
                                            <td v-if="!isMobile">{{mare.intercommunalite}}</td>
                                            <td v-if="!isMobile">{{mare.commune}}</td>
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
                                            <td class="text-center" v-if="types_selected && types_selected.id === 0">
                                                <button :class="mare.commentaires.length > 0 ? 'btn btn-info' : 'btn btn-secondary'" @click.prevent="showCommentaires(mare)">
                                                    <i class="fas fa-comments text-white mr-0"></i>
                                                    <template v-if="checkIfCommentaire(mare)">
                                                        <span class="badge badge-light position-absolute text-white bg-danger">{{mare.commentaires_utilisateurs_non_lus}}</span>
                                                    </template>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <template v-if="mare.statut && mare.statut.nom_interne === 'en_attente_de_validation'">
                                                    <router-link :to="'/mes_mares/edit/' + mare.id" class="btn btn-primary">
                                                        <span>
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    </router-link>
                                                </template>
                                                <template v-else>
                                                    <router-link :to="'/mes_mares/consultation/' + mare.id" class="btn btn-primary">
                                                        <span>
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                    </router-link>
                                                </template>
                                            </td>
                                            <td class="text-center" v-if="$user.role !='utilisateur'">
                                                <delete-parametre-data-component
                                                :idProps = "mare.id"
                                                :modelsProps = "mares"
                                                :confirmTextProps = "'Voulez-vous vraiment supprimer cette mare et toutes ses fiches ? Cette action est irréversible'"
                                                :modelNameProps = "$route.name"
                                                :urlProps = "$route.name"
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
                            <p class="p-4">{{message_aucune_mare}}</p>
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
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isLoading: true,
                first_loading: true,
                fullPage: false,
                currentSort: 'id',
                currentSortDir: 'asc',
                
                pages: 0,
                page: 1,
                perPage: 10,


                windowWidth: window.innerWidth,
                isMobile: false,
                searchColumns: ['nom'],
                all_data: [],
                mares: [],
                base_url: base_url,
                page_title: '',
                message_aucune_mare: '',
                edit_url: '',
                types: [],
                types_selected: [],
                mare_selected: [],
                statuts: [],
                statut_selected: [],
                mare_id: '',
                formulaires: null,
                utilisateur: null
            }
        },
        watch: {
            '$route' (to, from){
                if(to.name != 'mes_mares.commentaires' && from.name != 'mes_mares.commentaires'){
                    return this.filter();
                }
            }
        },
        methods: {
            clear: function(){
                this.mare_id = '';
                this.code_ofb = '';
                return this.filter();
                // this.user = [];
                // this.email = '';
            },
            selectedMare: function(mare) {
                // this.formData = new FormData();
                // this.route = '/recherche/get_mares_by_identifiant';
                // this.formData.append('mare_id', mare.value);
                this.mare_id = mare.value;
                this.page = 1;
                return this.filter();
            },
            filter: function(){
                let self = this;
                let formData = new FormData();
                let statut_id = '';
                let route = self.base_url + '/mes_mares/toutes_mes_mares';
                self.isLoading = true;

                if(self.objectSize(self.types_selected) > 0 && self.types_selected.id == 0){
                    route = self.base_url + '/mes_mares/toutes_mes_mares?page='+this.page;
                }else if(self.objectSize(self.types_selected) > 0 && self.types_selected.id != 0){
                    route = self.base_url + '/mes_mares/mares_du_groupe?page='+this.page;
                }

                if(this.objectSize(this.statut_selected) > 0 && this.statut_selected.id){
                    statut_id = this.statut_selected.id;
                }

                formData.append('mare_id', this.mare_id);
                formData.append('statut_id', statut_id);
                formData.append('per_page', self.perPage);
                formData.append('currentSort', self.currentSort);
                formData.append('currentSortDir', self.currentSortDir);

                axios.post(route, formData, { 
                    _method: 'POST',
                })
                .then(function (resp) {
                    self.all_data = resp.data.mares;
                    self.mares = resp.data.mares.data;
                    self.page = resp.data.mares.current_page;
                    self.pages = resp.data.mares.last_page;
                    self.perPage = resp.data.mares.per_page;
                    self.isLoading = false;
                })
                .catch(function (resp) {
                    return self.checkError(resp);
                });
            },
            updateMare: function(mare){
                let index = this.mares.findIndex(m => m.id === mare.id);

                if(index >= 0){
                    this.mares.splice(index, 1, mare);
                }
            },
            showCommentaires: function(mare){
                this.mare_selected = mare;
                this.$router.push({path: '/mes_mares/commentaires'});
            },
            checkIfCommentaire: function(mare){
                if(mare.commentaires_utilisateurs_non_lus && mare.commentaires_utilisateurs_non_lus > 0){
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
            deleteData: function(event){
                return this.filter();
            },
            checkIfUserCanChangeInput: function(input){
                if(this.utilisateur && this.utilisateur.role && this.utilisateur.role.nom_interne && (this.utilisateur.role.nom_interne == 'gestionnaire' || this.utilisateur.role.nom_interne == 'developpeur' || this.utilisateur.role.nom_interne == 'administrateur')){
                    return true;
                }

                let form = this.formulaires.find(f => f.nom_interne === input);
                if(form && form.groupes && this.utilisateur.groupe && this.utilisateur.groupe.id){
                    let autorisation = form.groupes.find(g => g.id === this.utilisateur.groupe.id);
                    if(autorisation != undefined){
                        return true;
                    }
                }

                return false;
            },
        },
        mounted:function(){
            let self = this;
            axios.get(self.base_url + '/mes_mares')
            .then(function (resp) {
                self.utilisateur = resp.data.utilisateur;
                self.types = resp.data.types;
                self.types_selected = resp.data.types_selected;
                self.statuts = resp.data.statuts;
                self.formulaires = resp.data.formulaires;
                self.first_loading = false;
                self.isLoading = false;
                return self.filter();
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
    };
</script>
