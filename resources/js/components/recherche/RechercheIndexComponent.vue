<template>
	<div class="position-relative">
		<entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 70vh!important;">
        	<div class="card">
	            <div class="card-header">
	                <h5><i class="far fa-search"></i> Que recherchez-vous ?</h5>
	            </div>
	            <div class="card-body">
	            	<div class="w-100 d-flex flex-wrap">
		            	<div class="col-4 mb-4">
							<multiselect 
							v-model="type_selected"
							:options="types" 
							deselectLabel="" 
							track-by="nom" 
							label="nom"
							placeholder="Rechercher par"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							@input="checkType()"
							>
							</multiselect>
						</div>

						<div class="col-4 mb-4" v-if="filter_by_identifiant_mare">
							<autocomplete
	                            ref="autocomplete"
	                            placeholder="Chercher une mare"
	                            :source="base_url + '/recherche/search_mare?q='"
	                            input-class="form-control"
	                            results-property="mares"
	                            results-display="label"
	                            @selected="selectedMare"
	                            @clear="clear">
	                        </autocomplete>
	                    </div>

						<div class="col-4 mb-4" v-if="filter_by_departement">
							<multiselect 
							v-model="departements_selected"
							:options="departements" 
							deselectLabel="" 
							track-by="id" 
							label="nom"
							placeholder="Département"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							@input="filterByDepartement()"
							>
							</multiselect>
						</div>

						<div class="col-4 mb-4" v-if="filter_by_intercommunalite">
							<multiselect 
							v-model="intercommunalites_selected"
							:options="intercommunalites" 
							deselectLabel="" 
							track-by="id" 
							label="raison_soc"
							placeholder="Intercommunalités"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							@input="filterByIntercommunalite()"
							>
							</multiselect>
						</div>

						<div class="col-4 mb-4" v-if="filter_by_commune">
							<multiselect 
							v-model="communes_selected"
							:options="communes" 
							deselectLabel="" 
							track-by="id" 
							label="label"
							placeholder="Communes"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							@input="filterByCommune()"
							>
							</multiselect>
						</div>
					</div>
	            </div>
	        </div>

	        <div class="card mt-4" v-if="mares.length > 0">
	            <div class="card-header">
	                <p class="m-0 p-1">{{all_data && all_data.total ? all_data.total : ''}} résultat(s) pour votre recherche</p>
	            </div>
	            <div class="card-body">
	            	<div class="w-100 d-flex flex-wrap">

	            		<div class="ml-auto mb-4">
                            <perPage v-if="!isMobile"
                            v-on:setPages="checkPages"
                            ></perPage>
                        </div>

	            		<table class="table table-striped table-responsive-sm">
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
                                    <th class="text-center">Consulter</th>
                                    <th class="text-center">Créer une fiche</th>

                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="mare in mares">
                                    <td>{{mare.id}}</td>
                                    <td v-if="!isMobile">{{mare.nom}}</td>
                                    <td class="text-center">{{mare.departement_code}}</td>
                                    <td v-if="!isMobile">{{mare.intercommunalite}}</td>
                                    <td v-if="!isMobile">{{mare.commune}}</td>
                                    <td class="text-center">
                                    	<router-link :to="'/recherche/consultation/' + mare.id" class="btn btn-primary">
                                            <span>
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </router-link>
                                    </td>
                                    <td class="text-center">
                                    	<router-link :to="'/recherche/consultation/' + mare.id + '/nouvelle_fiche'" class="btn btn-success">
                                            <span>
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
					</div>

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
        </b-container>

        <router-view
            name="second"
            :mare="mare_selected"
            :type="'mare'"
        ></router-view>

    </div>
</template>

<script>

export default{
	data(){
		return{
			isLoading: true,
			isMobile: false,
			base_url: base_url,
			all_data: [],
			errors: [],
			mares: [],
			mare_selected: [],

			types: [],
			type_selected: [],
			departements: [],
			departements_selected: [],
			intercommunalites: [],
			intercommunalites_selected: [],
			communes: [],
			communes_selected: [],

			filter_by_identifiant_mare: false,
			filter_by_departement: false,
			filter_by_intercommunalite: false,
			filter_by_commune: false,

			currentSort: 'id',
            currentSortDir: 'asc',
			pages: 0,
            page: 1,
            perPage: 10,

            route: '',
            formData: [],
		}
	},
	methods:{
		formulaire: function(){
			let self = this;
			self.isLoading = true;
            self.errors = [];

            this.formData.append('per_page', self.perPage);
            this.formData.append('currentSort', self.currentSort);
            this.formData.append('currentSortDir', self.currentSortDir);

            axios.post(base_url + this.route + '?page='+this.page, this.formData, { 
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
            .catch(function (error) { 
                return self.checkError(error); 
            });
		},
		filterByDepartement: function(){
			this.formData = new FormData();
			this.route = '/recherche/get_mares_by_departement';
			this.formData.append('departement_code', this.departements_selected && this.departements_selected.code_insee ? this.departements_selected.code_insee : '');
			return this.formulaire();
		},
		filterByIntercommunalite: function(){
			this.formData = new FormData();
			this.route = '/recherche/get_mares_by_intercommunalite';
			this.formData.append('intercommunalite_siren', this.intercommunalites_selected && this.intercommunalites_selected.siren ? this.intercommunalites_selected.siren : '');
			return this.formulaire();
		},
		filterByCommune: function(){
			this.formData = new FormData();
			this.route = '/recherche/get_mares_by_commune';
			this.formData.append('commune_insee', this.communes_selected && this.communes_selected.insee ? this.communes_selected.insee : '');
			return this.formulaire();
		},
		selectedMare: function(mare) {
			this.formData = new FormData();
			this.route = '/recherche/get_mares_by_identifiant';
			this.formData.append('mare_id', mare.value);
			return this.formulaire();
        },
        clear: function(){
            // this.user = [];
            // this.email = '';
        },
		checkType: function(){
			this.resetFilter();

			if(this.type_selected && this.type_selected.nom_interne && this.type_selected.nom_interne === 'identifiant_de_mare'){
				this.filter_by_identifiant_mare = true;
			}else if(this.type_selected && this.type_selected.nom_interne && this.type_selected.nom_interne === 'departements'){
				this.filter_by_departement = true;
			}else if(this.type_selected && this.type_selected.nom_interne && this.type_selected.nom_interne === 'intercommunalites'){
				this.filter_by_intercommunalite = true;
			}else if(this.type_selected && this.type_selected.nom_interne && this.type_selected.nom_interne === 'communes'){
				this.filter_by_commune = true;
			}
		},
		resetFilter: function(){
			this.filter_by_identifiant_mare = false;
			this.filter_by_departement = false;
			this.filter_by_intercommunalite = false;
			this.filter_by_commune = false;
		},
		changePage: function(val){
            this.page = val;
            this.formulaire();
        },
        checkPages: function(event){
            this.perPage = event;
            this.formulaire();
        },
        checkCurrentSortDir: function(event){
            this.currentSortDir = event;
            this.formulaire();
        },
        checkCurrentSort: function(event){
            this.currentSort = event;
            this.formulaire();
        },
	},
	mounted(){
        const self = this;
        axios.get(base_url + '/recherche')
        .then(function (resp) {
            self.isLoading = true;
            self.departements = resp.data.departements;
            self.intercommunalites = resp.data.intercommunalites;
            self.communes = resp.data.communes;
            self.types = resp.data.types;
            self.isLoading = false;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    }
}


</script>