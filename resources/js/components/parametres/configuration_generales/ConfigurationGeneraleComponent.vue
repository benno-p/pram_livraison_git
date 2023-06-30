<template>
	<div class="position-relative" ref="container">
        <entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
        	<div class="card border-0">
	            <div class="card-body">
	           		<div v-if="!isLoading">
		                <form method="POST" action="" v-on:submit.prevent="create()">
		                	<div class="card">
		                		<div class="card-header">
		                			<h3>Page Accueil, image et logo</h3>
		                		</div>

		                		<div class="card-body">
				                	<div class="form-group">
										<label class="col-12 col-md- pl-0">Texte sur l'image de la page d'accueil<span class="text-danger">*</span></label>
										<vue-editor v-model="titre_image_accueil" :editorToolbar="customToolbar"></vue-editor>
									</div>

									<!-- <hr> -->
									<div class="form-group">
										<label class="col-12 col-md-2 pl-0">Titre de la page d'accueil<span class="text-danger">*</span></label>
										<vue-editor v-model="titre_accueil" :editorToolbar="customToolbar"></vue-editor>
									</div>

									<hr>

									<div class="form-group">
										<label class="col-12 col-md-2 pl-0">Texte de la page d'accueil<span class="text-danger">*</span></label>
										<vue-editor v-model="texte_accueil" :editorToolbar="customToolbar"></vue-editor>
									</div>

									<hr>

									<file-upload-component
					                :titre="'Image page d\'accueil'"
					                :accept="'.jpg,.JPG,.png,.PNG,.jpeg,.JPEG'"
					                :label="'Image (format jpg, jpeg, png - 5mo maximum)'"
					                :fichier_exist="image_page_accueil_url"
					                v-on:fichierFromFileUploadComponent="imageAccueilFromFileUploadComponent"
					                ></file-upload-component>

					                <hr>

					                <file-upload-component
					                :titre="'Logo'"
					                :accept="'.jpg,.JPG,.png,.PNG,.jpeg,.JPEG'"
					                :label="'Logo (format jpg, jpeg, png - 5mo maximum)'"
					                :fichier_exist="logo_url"
					                v-on:fichierFromFileUploadComponent="logoFromFileUploadComponent"
					                ></file-upload-component>

					                <div class="form-group">
					                	<div class="col-12 p-0">
				                            <label class="col-12 col-form-label pl-0">URL de redirection lors du clique sur le logo sur la page d'accueil</label>
				                            <div class="col-12 pl-0">
				                                <input type="text"class="form-control" v-model="redirect_url_logo">
				                            </div>
				                        </div>
				                    </div>
					            </div>
					        </div>

			                <div class="card mt-4">
		                		<div class="card-header">
		                			<h3>Région GEOJSON, centroide par défaut</h3>
		                		</div>

		                		<div class="card-body">
		                			<div class="form-group row">
			                        	<div class="col-12 col-md-6">
				                            <label class="col-12 col-form-label pl-0">Latitude Centroide carte par défaut (WGS84)</label>
				                            <div class="col-12 pl-0">
				                                <input type="number" step="0.0000001" class="form-control" v-model="lat">
				                            </div>
				                        </div>

				                        <div class="col-12 col-md-6">
				                            <label class="col-12 col-form-label pl-0">Longitude Centroide carte par défaut (WGS84)</label>
				                            <div class="col-12 pl-0">
				                                <input type="number" step="0.0000001" class="form-control" v-model="lng">
				                            </div>
				                        </div>
				                    </div>

				                    <hr>

				                    <file-upload-component
					                :titre="'Région GEOJSON'"
					                :accept="'.geojson'"
					                :label="'Contour geojson de la région (format geojson)'"
					                :fichier_exist="region_geojson_url"
					                v-on:fichierFromFileUploadComponent="regionGeojsonFromFileUploadComponent"
					                ></file-upload-component>

		                		</div>
		                	</div>


							<div class="col-12 mt-4">
								<button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>

								<router-link to="/parametres" class="btn btn-light">
	                                <i class="fas fa-times-circle"></i> Retour
	                            </router-link>
							</div>
		                </form>
		            </div>
	            </div>
	        </div>
	    </b-container>
	</div>
</template>


<script>
import { VueEditor } from "vue2-editor";

export default{
	components: {
        VueEditor
    },
	data(){
		return{
			isLoading: true,
			formulaire_soumis: false,
			base_url: base_url,
			errors: [],
			titre_image_accueil: '',
			titre_accueil: '',
			texte_accueil: '',
			
			image_page_accueil: '',
			image_page_accueil_url: '',
			modifier_image_page_accueil: false,
			
			logo: '',
			logo_url: '',
			redirect_url_logo: '',
			modifier_logo: false,
			configuration: [],

			lat: '',
			lng: '',

			region_geojson: [],
			modifier_region_geojson: false,
			region_geojson_url: '',
		}
	},
	created:function(){
        this.customToolbar = this.paramsForCustomEditorToolbar();
    },
    mounted(){
    	let self = this;
    	axios.get(base_url + '/configuration/configurations_generales')
        .then(function (resp) {
        	self.configuration = resp.data.configuration;

        	if(self.configuration){
        		self.titre_accueil = self.configuration.titre_accueil;
        		self.texte_accueil = self.configuration.texte_accueil;
        		self.titre_image_accueil = self.configuration.titre_image_accueil;

        		self.image_page_accueil_url = self.configuration.image_page_accueil ? self.base_url + '/configuration/image_page_accueil/'+self.configuration.image_page_accueil : '';
        		self.logo_url = self.configuration.logo ? self.base_url + '/configuration/logo/'+self.configuration.logo : '';

        		self.lat = self.configuration.centroide_lat ? self.configuration.centroide_lat : '';
        		self.lng = self.configuration.centroide_lng ? self.configuration.centroide_lng : '';

        		self.region_geojson_url = self.configuration.path_region_geojson ? self.base_url + '/configuration/region_geojson/'+self.configuration.path_region_geojson : '';
        		self.redirect_url_logo = self.configuration.redirect_url_logo ? self.configuration.redirect_url_logo : '';
        	}

            self.isLoading = false;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    },
	methods:{
		imageAccueilFromFileUploadComponent: function(event){
			this.image_page_accueil = event.fichier;
			this.modifier_image_page_accueil = event.modifier_fichier;
		},
		logoFromFileUploadComponent: function(event){
			this.logo = event.fichier;
			this.modifier_logo = event.modifier_fichier;
		},
		regionGeojsonFromFileUploadComponent: function(event){
			this.region_geojson = event.fichier;
			this.modifier_region_geojson = event.modifier_fichier;
		},
		create: function(){
			let self = this;
			self.isLoading = true;
			self.errors = [];

			if(!self.titre_image_accueil){
                self.errors.push('Le champ Titre de l\'image d\'accueil est obligatoire');
            }

            if(self.errors.length){
                self.showErrors(self.errors);
                self.isLoading = false;
                return false;
            }


			let formData = new FormData();

			formData.append('titre_image_accueil', self.titre_image_accueil ? self.titre_image_accueil : '');
            formData.append('titre_accueil', self.titre_accueil ? self.titre_accueil : '');
            formData.append('texte_accueil', self.texte_accueil ? self.texte_accueil : '');

            formData.append('image_page_accueil', self.image_page_accueil ? self.image_page_accueil : '');
            formData.append('modifier_image_page_accueil', self.modifier_image_page_accueil === true ? '1' : '0');

            formData.append('logo', self.logo ? self.logo : '');
            formData.append('modifier_logo', self.modifier_logo === true ? '1' : '0');

            formData.append('lat', self.lat ? self.lat : '');
            formData.append('lng', self.lng ? self.lng : '');

            formData.append('region_geojson', self.region_geojson ? self.region_geojson : '');
            formData.append('modifier_region_geojson', self.modifier_region_geojson === true ? '1' : '0');

            formData.append('redirect_url_logo', self.redirect_url_logo ? self.redirect_url_logo : '');

            axios.post(base_url + '/configuration/update_configurations_generales', formData, { 
                _method: 'POST',
            })
            .then(function (response) {
                if(response.data.error == true){
                    Object.keys(response.data.message).map(function(objectKey, index) {
                        let value = response.data.message[objectKey];
                        self.errors.push(value[0]);
                    });
                    self.showErrors(self.errors);
                    self.isLoading = false;
                    return false;
                }else{
					if(response.status === 200){
						self.showSuccess(response.data.message)
                        self.$router.push({path: '/parametres'});
                        return self.isLoading = false;
                	}
                }
            })
            .catch(function (error) { 
                self.isLoading = false;
                return self.checkError(error); 
            });	
		}
	}
}
</script>