<template>
	<div class="position-relative">
		<entete-layout :isLoading="isLoading"></entete-layout>

		<b-container fluid class="mt--7" style="min-height: 70vh!important;">
        	<div class="card">
	            <div class="card-header">
	                <h5><i class="fas fa-filter" ref="icon_i_filters"></i> Filtres</h5>
	            </div>

	            <div class="card-body pl-2 pr-2 pt-2">
	            	<div class="w-100 d-flex flex-wrap">
	            		<div class="col-12 col-md-4 mt-2">
	                        <label class="typo__label h4 mb-3">Type de vue</label>
							<multiselect
							v-model="type"
							:options="types" 
							deselectLabel="" 
							track-by="nom" 
							label="nom"
							placeholder="Selectionner un type d'export"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							>
							<span slot="noOptions">Liste vide</span> <span slot="noResult">Aucun résultat</span>
							</multiselect>
						</div>

						<div class="col-12 col-md-4 mt-2" v-if="objectSize(departements) > 0">
	                        <label class="typo__label h4 mb-3">Département</label>
							<multiselect
							v-model="departement"
							:options="departements" 
							deselectLabel="" 
							track-by="id" 
							label="nom"
							placeholder="Selectionner un département"
							selectLabel=""
							:searchable="true" 
							:allow-empty="false"
							>
							<span slot="noOptions">Liste vide</span> <span slot="noResult">Aucun résultat</span>
							</multiselect>
						</div>
					</div>

					<hr>

					<template v-if="objectSize(type) > 0">
						<div class="w-100 d-flex flex-wrap pl-3">
		            		<label class="typo__label col-12 p-0 h4">Colonnes</span></label>

		            		<div class="card w-100 mt-4" v-for="(t, type_key) in type.colonnes">
		            			<div class="card-header">
		            				{{type_key.toUpperCase()}}
		            				<div class="float-right">
			            				<button class="btn btn-warning btn-sm" @click="selectDeselectColonnes('deselect', type_key, type.colonnes)">
			            					Tout désélectionner
			            				</button>
			            				<button class="btn btn-primary btn-sm" @click="selectDeselectColonnes('select', type_key, type.colonnes)">
			            					Tout sélectionner
			            				</button>
			            			</div>
		            			</div>

		            			<div class="card-body d-flex flex-wrap">
			            			<div class="form-check col-2 mb-2" v-for="(col, key) in t">
			                            <input class="form-check-input" type="checkbox" :id="'checkbox_type_colonne_'+t+'_'+key" v-model="t[key]">
			                            <label class="form-check-label" :for="'checkbox_type_colonne_'+t+'_'+key">
			                                {{sanitizeKeyColonne(key)}}
			                            </label>
			                        </div>
			                    </div>
		            		</div>
		            	</div>

		            	<hr>

	            		<div class="col-12 p-0" v-if="need_interval">
            				<div class="alert alert-warning d-flex flex-wrap align-items-center" role="alert">
            					<div class="col-12 col-md-6">
							 		{{interval_message}}
							 	</div>
							 	<div class="col-12 col-md-6">
								 	<multiselect
									v-model="interval"
									:options="intervals" 
									deselectLabel="" 
									track-by="skip" 
									label="label"
									placeholder="Selectionner un intervalle"
									selectLabel=""
									:searchable="true" 
									:allow-empty="false"
									>
									</multiselect>
								</div>
							</div>
						</div>

                        <button class="btn btn-success float-right" @click="submit()">
                            <i class="fas fa-file-export"></i> Exporter
                        </button>
					</template>

	            </div>
	        </div>
	    </b-container>
	</div>
</template>

<script>
export default{
	data(){
		return{
			isLoading: true,
			isMobile: false,
			base_url: base_url,
			errors: [],

			types: [],
			type: [],

			interval_message: '',
			need_interval: false,
			intervals: [],
			interval: null,
			departements: [],
			departement: [],
		}
	},
	watch: {
		type: function(){
			this.interval_message = '';
			this.need_interval = false;
			this.intervals = [];
			this.interval = null;
		}
	},
	methods: {
		sanitizeKeyColonne: function(key){
			let string = key;

			switch (string) {
			case 'id':
				string = 'Identifiant mare';
			break;
			case 'x_l93':
				string = 'X Lambert 93';
			break;
			case 'y_l93':
				string = 'Y Lambert 93';
			break;
			case 'lng':
				string = 'Longitude';
			break;
			case 'lat':
				string = 'Latitude';
			break;
			case 'situation_topographie':
				string = 'Topographie';
			break;
			default:
			}

			string = this.capitalizeFirstLetter(string.replaceAll('_', ' '));
			return string;

		},
		selectDeselectColonnes: function(selection, type_key, type_colonne){
			if(selection === 'deselect'){
				Object.keys(type_colonne[type_key]).forEach(function(col){
					type_colonne[type_key][col] = false;
				});
			}else if(selection === 'select'){
				Object.keys(type_colonne[type_key]).forEach(function(col){
					type_colonne[type_key][col] = true;
				});
			}
		},
		submit: function(){
			let self = this;
			let formData = new FormData();
			self.isLoading = true;
    		self.errors = [];

    		// return console.log(self.departement);

    		formData.append('type', self.type && self.type ? JSON.stringify(self.type) : '');
    		formData.append('skip', self.interval != null ? self.interval.skip : '');
    		formData.append('departement_code', self.departement && self.departement.code_insee ? self.departement.code_insee : '');

    		axios.post(base_url + '/exports/exports', formData,{
                _method: 'POST',
            })
            .then(function (response) {
                if(response.data.error == true){
					Object.keys(response.data.message).map(function(objectKey, index) {
					    let value = response.data.message[objectKey];
					    self.errors.push(value[0]);
					});
					self.isLoading = false;
                    self.showErrors(self.errors);
                    return false;
                }else{
                    if(response.data.statut == 'success'){
                    	self.isLoading = false;
                        window.location.href = response.data.url;
                    }else{
                    	self.need_interval = true;
                    	self.interval_message = response.data.message;
                    	self.intervals = response.data.intervals;
                    }

                    self.isLoading = false;
                }
            })
            .catch(function (resp) {
            	self.isLoading = false;
                return self.checkError(resp);
            });
		}
	},
	mounted(){
        const self = this;
        axios.get(base_url + '/exports/index')
        .then(function (resp) {
            self.isLoading = true;
            self.types = resp.data.types;
            self.departements = resp.data.departements;

            if(self.objectSize(self.departements) === 1){
            	self.departement = self.departements[0];
            }

            self.isLoading = false;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    }
}
</script>

<style>
.form-check input:hover {
    cursor: pointer;
}
.form-check label:hover {
    cursor: pointer;
}
</style>