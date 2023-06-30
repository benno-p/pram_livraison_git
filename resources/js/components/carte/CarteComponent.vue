<template>
	<div v-bind:style="{ height: computedHeight }" class="position-relative">
		<router-view
			:infoNouvelleMareProps="infoNouvelleMare"
			:latitudeNouvelleMare="latitude_nouvelle_mare"
			:longitudeNouvelleMare="longitude_nouvelle_mare"
			v-on:checkIfIsLoading="checkIfIsLoading"
			name="second"
		></router-view>
		<div class="vld-parent">
            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
        </div>

        <filtres-carte-component v-if="show"
        :departementsProps="departements"
		:intercommunalitesProps="intercommunalites"
		:communesProps="communes"
		:maresProps="mares"
		:totalMaresProps="total_mares"
		:totalToutesMaresProps="total_toutes_mares"
		:nbMaresByFilters="nb_mares_by_filters"
		v-on:departementChange="filterByDepartement"
		v-on:intercommunaliteChange="filterByIntercommunalite"
		v-on:communeChange="filterByCommune"
		v-on:mareChange="filterByMare"
		v-on:reinitialiseFiltre="reinitialiseFiltre"
		v-on:loadAllMares="loadAllMares"
		v-on:changeShowMareFilter="changeShowMareFilter"
        >
        </filtres-carte-component>

        <router-link to="/" class="btn btn-dark" id="bouton_retour_accueil" v-if="$user.authenticated == false && $user.role == 'visiteur'">
            <i class="fas fa-home"></i> Retour à l'accueil
        </router-link>

		<l-map @click="addMarker"
			ref="map"
			style="height: 100%; width: 100%"
			:zoom="zoom"
			:center="center"
			@move="doSomething"
			>

			<l-control-layers position="topleft"></l-control-layers>

			<!-- URL de la MAP -->
			<l-tile-layer
		     	v-for="tileProvider in tileProviders"
		    	:key="tileProvider.name"
		    	:name="tileProvider.name"
		    	:visible="tileProvider.visible"
		    	:url="tileProvider.url"
		    	:attribution="tileProvider.attribution"
		    	layer-type="base"/>

			

			<!-- Contour Grand Est -->
			<l-geo-json
			v-if="show"
			:geojson="grand_est_geo_json"
			:options-style="grandEstGeoJsonStyleFunction"
			/>

			<!-- Contour departement / intercommunalite / commune  -->
			<l-geo-json
			v-if="show"
			:geojson="geojson"
			:options-style="styleFunction"
			/>

			<v-locatecontrol/>
		</l-map>
	</div>
</template>

<script>
	import { LMap, LTileLayer, LGeoJson, LCircleMarker, LPopup, LControlLayers } from 'vue2-leaflet';
	import { latLng } from 'leaflet';
	import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster';
	import 'leaflet/dist/leaflet.css';

	import Vue2LeafletLocatecontrol from 'vue2-leaflet-locatecontrol';


	export default {
		/*
		 * Charge les composants de Leaflet
		 */
		components: {
			LMap,
			LTileLayer,
			LGeoJson,
			LCircleMarker,
			LPopup,
			LControlLayers,
			'v-locatecontrol': Vue2LeafletLocatecontrol
		},
		/*
		 * Initialisation des variables
		 */
		data () {
			return {
				copie_mares_data: [],
				nb_mares_by_filters: 0,
				isLoading: true,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                // height: '95vh',
                height: '100vh',
                all_mares_loaded: false,
                markerClusterGroup: [],

                map: null,
				all_data: [],
				departements: [],
				departement: 0,
				intercommunalites: [],
				intercommunalite: 0,
				communes: [],
				commune: 0,
				mares: [],
				total_mares: 0,
				total_toutes_mares: 0,
				mare: 0,
				url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
				zoom: 8,
				// zoom: 20,
				zoom_niveau: 8,
				center: this.$config.centroide_lat && this.$config.centroide_lng ? [this.$config.centroide_lat, this.$config.centroide_lng] : [48.6833, 6.2],
				grand_est_geo_json: null,
				geojson: null,
				fillColor: "green",
				show: false,
				infoNouvelleMare: [],
				latitude_nouvelle_mare: '',
				longitude_nouvelle_mare: '',
				tileProviders: [
			        {
			          name: 'OpenStreetMap',
			          visible: true,
			          attribution:
			            '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
			          url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			        },
			        {
			          name: 'Esri_WorldImagery',
			          visible: false,
			          url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}.png',
			          attribution:
			            'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
			        },
			        {
			          name: 'Scan 25',
			          visible: false,
			          url: 'https://wxs.ign.fr/ncmnfxjqu1x6t9memmqp1zl5/geoportail/wmts?LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}',
			          apiKey: 'ncmnfxjqu1x6t9memmqp1zl5',
			          attribution:
			            '&copy; <a href="http://www.ign.fr/">IGN</a>',
			        },
			        {
			          name: 'Orthophotos',
			          visible: false,
			          url: 'https://wxs.ign.fr/essentiels/geoportail/wmts?LAYER=ORTHOIMAGERY.ORTHOPHOTOS&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}',
			          attribution:
			            'a',
			        },
			        {
			          name: 'Cadastre',
			          visible: false,
			          url: 'https://wxs.ign.fr/parcellaire/geoportail/wmts?LAYER=CADASTRALPARCELS.PARCELS&EXCEPTIONS=text/xml&FORMAT=image/png&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}',
			          attribution:
			            '&copy; <a href="http://www.ign.fr/">IGN</a>',
			        },

			    ],
			};
		},
		/*
		 * Les méthodes/fonctions
		 */
		methods: {
			changeShowMareFilter(filters){
				this.displayMares(this.copie_mares_data, null, filters);
			},
			loadAllMares: function(event){
				if(event === true && this.all_mares_loaded === false){
					this.all_mares_loaded = true;
					return this.loadMares('/carte/load_all_mares');
				}else if(event === true && this.all_mares_loaded === true){
					this.isLoading = true
					return this.displayMares(this.all_data.mares);
				}else{
					this.map.removeLayer(this.markerClusterGroup);
				}
			},
			checkIfIsLoading: function(event){
				this.isLoading = event;
			},
			doSomething: function(event){
				this.zoom_niveau = event.target._animateToZoom;
			},
			addMarker(event) {
				let self = this;
				if(this.$user != null && this.$user.authenticated == true){

					if(this.zoom_niveau < 13){
						return alert('Pour créer une mare, vous devez zommer d\'avantage');
					}

					if(confirm("Souhaitez-vous créer une mare à l'endroit indiqué ?")){
						let formData = new FormData;

						let coordonnee_nouvelle_mare = event.latlng;

						self.latitude_nouvelle_mare = coordonnee_nouvelle_mare.lat;
						self.longitude_nouvelle_mare = coordonnee_nouvelle_mare.lng;

						formData.append('latitude', coordonnee_nouvelle_mare.lat);
						formData.append('longitude', coordonnee_nouvelle_mare.lng);
						
						axios.post(base_url + '/mares/find_info_mare', formData, { 
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
		                        self.isLoading = false;
		                        self.infoNouvelleMare = response.data.infos;
		                        self.$router.push({path: '/carte/create'});
		                    }
		                })
		                .catch(function (error) { 
		                    self.isLoading = false;
		                    return self.checkError(error); 
		                });
					}
				}
			},
			modifierMare: function(id){
				this.$router.push({path: '/carte/edition/'+id});
			},
			reinitialiseFiltre: function(){
				this.$nextTick(() => {
					this.nb_mares_by_filters = 0;
					this.center = [];
					this.zoom = 7;
					this.departement = 0;
					this.intercommunalite = 0;
					this.commune = 0;
					this.mare = 0;
					this.center = [48.6833, 6.2];
					this.geojson = [];
					this.intercommunalites = [];
					this.total_mares = this.mares.length;
					this.total_mares = this.all_data.total_mares;

					if(this.all_mares_loaded === false){
						this.map.removeLayer(this.markerClusterGroup);
					}

					let self = this;
					setTimeout(function(){
						self.zoom = 8;
					}, 500)
				});
			},
			filterByDepartement: function(event){
				this.departement = event;
				if(this.departement != 0){
					this.intercommunalite = 0;
					this.commune = 0;
					this.mare = 0;
					this.filterForm('/carte/load_intercommunalite_from_departement/', this.departement, 9, 'intercommunalites');
					this.filterForm('/carte/load_commune_from_departement/', this.departement, 9, 'communes');
				}
			},
			filterByIntercommunalite: function(event){
				this.intercommunalite = event;
				if(this.intercommunalite != 0){
					this.commune = 0;
					this.mare = 0;
					this.filterForm('/carte/load_commune_from_intercommunalite/', this.intercommunalite, 12, 'communes');
				}
			},
			filterByCommune: function(event){
				this.commune = event;
				if(this.commune != 0){
					this.mare = 0;
					this.filterForm('/carte/load_mare_from_commune/', this.commune, 13, 'mares');	
				}
			},
			filterByMare: function(event){
				this.mare = event;
				if(this.mare != 0){
					this.filterForm('/carte/load_mares/', this.mare, 15, undefined);
				}
			},
			filterForm: function(url, id, zoom, filters){
				const self = this;
				self.isLoading = true;
	            axios.get(base_url + url + id + '/' + self.all_mares_loaded)
	            .then(function (resp) {
	            	if(filters != undefined){
	            		self[filters] = resp.data.data;
	            		self.total_mares = resp.data.total_mares;
	            	}
	            	
	            	if(self.objectSize(resp.data.geojson) > 0){
	            		self.geojson = resp.data.geojson;
	            	}
	            	
	            	if(self.objectSize(resp.data.centroide) > 0){
	            		self.center = [resp.data.centroide.coord_y, resp.data.centroide.coord_x];
	            	}

	            	if(self.objectSize(resp.data.mares) > 0 && self.all_mares_loaded === false){
	            		return self.displayMares(resp.data.mares, zoom);
	            	}else if(self.objectSize(resp.data.centroide) > 0){
	            		setTimeout(function(){
							self.zoom = zoom;
							self.isLoading = false;
						}, 500)
	            	}
	            })
	            .catch(function (resp) {
	                return self.checkError(resp);
	            });     
			},
			popupContent: function(mare){
				let content = '<p> Identifiant : '+mare.id+'</p><p>'+mare.type_principale+'</p>';
				
				content = content + '<a href="#/carte/consultation/'+mare.id+'" class="btn btn-primary btn-sm bouton-perso"> <i class="fas fa-eye"></i> Consulter</a>';

				if(this.$user != null && this.$user.authenticated === true){
					content = content + '<a href="#/carte/consultation/'+mare.id+'/nouvelle_fiche" class="btn btn-success btn-sm bouton-perso"> <i class="fas fa-edit"></i> Créer une fiche</a>'; 
				}
				return content;
			},
			displayMares: function(data, zoom = null, type_mare_filters = null){
				this.copie_mares_data = data;
				
				const self = this;

				self.isLoading = true;
				self.map.removeLayer(self.markerClusterGroup);

				this.$nextTick(() => {
			    	self.map = self.$refs.map.mapObject;
			    });

				const markerClusterGroup = L.markerClusterGroup({
					chunkedLoading: true,
				});
				self.markerClusterGroup = [];

				const markers = [];

				let d = [];

				if(type_mare_filters != null && (type_mare_filters.show_mare_caracterise === true || type_mare_filters.show_mare_vue === true || type_mare_filters.show_mare_potentielle === true || type_mare_filters.show_mare_disparue === true)){

					let result = [];
					// let sub_result = [];

					if(type_mare_filters.show_mare_caracterise === true){
						result = data.filter(d => d.couleur == '#09589D');
						d = result;
					}

					if(type_mare_filters.show_mare_vue === true){
						result = data.filter(d => d.couleur == '#84B826');
						d = d.concat(result);
					}

					if(type_mare_filters.show_mare_potentielle === true){
						result = data.filter(d => d.couleur == '#dc3545');
						d = d.concat(result);
					}

					if(type_mare_filters.show_mare_disparue === true){
						result = data.filter(d => d.couleur == '#343a40');
						d = d.concat(result);
					}
				}else{
					d = data;
				}

				self.nb_mares_by_filters = self.objectSize(d);

				if(self.objectSize(d) > 0){
					d.forEach(function(mare){
						let marker = L.circleMarker([
								mare.latitude, mare.longitude   
							], {
								color: mare.couleur,
								title: mare.nom,
								icon: '',
							}).bindPopup(
								self.popupContent(mare)
							);
						markers.push(marker)
					});

					markerClusterGroup.addLayers(markers)
					self.markerClusterGroup = markerClusterGroup;

					self.$nextTick(() => {
				    	self.map = self.$refs.map.mapObject;
				    	self.map.addLayer(markerClusterGroup);

				    	if(zoom){
				    		setTimeout(function(){
								self.zoom = zoom;
							}, 500)
				    	}

				    	setTimeout(function(){
							self.isLoading = false;
						}, 500)	
				    });
				}else{
					self.isLoading = false;
				}
				
			},
			loadMares: function(route){
				const self = this;
				self.isLoading = true;
				let mares = [];

	            axios.get(base_url + route)
	            .then(function (resp) {
	            	self.all_data.mares = resp.data.mares;
	            	return self.displayMares(resp.data.mares); 	
	            })
	            .catch(function (resp) {
	                return self.checkError(resp);
	            });
			}
		},
		/*
		 * Méthode qui se lance une fois le composant chargé
		 * On appelle l'url load_carte_data et on récupère les données en les affectants au variable que l'on a initialisé plus haut
		 */
		mounted(){
			const self = this;

			this.$nextTick(() => {
		    	self.map = self.$refs.map.mapObject;
		    });

			const markerClusterGroup = L.markerClusterGroup({
				chunkedLoading: true,
			});

            axios.get(base_url + '/carte/load_carte_data')
            .then(function (resp) {
            	self.all_data = resp.data;
            	self.departements = resp.data.departements;
            	self.grand_est_geo_json = resp.data.grand_est_geo_json;
            	self.total_mares = resp.data.total_mares;
            	self.total_toutes_mares = resp.data.total_mares;
            	self.show = true;
            	self.isLoading = false;   	
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
		},
		/*
		 * Les méthodes ci-dessous permettent de crééer un contour du Grand EsT
		 */
		computed: {
			grandEstGeoJsonStyleFunction() {
				const fillColor = this.fillColor;
				return () => {
					return {
						weight: 10,
						color: "black",
						opacity: 0.2,
						fillColor: fillColor,
						fillOpacity: 0.1,
					};
				};
			},
			styleFunction() {
				const fillColor = this.fillColor;
				return () => {
					return {
						weight: 3,
						color: "red",
						opacity: 1,
						fillColor: fillColor,
						fillOpacity: 0,
					};
				};
			},
			computedHeight: function () {
                if(this.$user.authenticated == true){
                    this.height = '89vh';
                }
                return this.height;
            }
		},
	}
</script>


<style>
	@import "~leaflet.markercluster/dist/MarkerCluster.css";
	@import "~leaflet.markercluster/dist/MarkerCluster.Default.css";
	.bouton-perso{
		color: white!important;
	}
</style>