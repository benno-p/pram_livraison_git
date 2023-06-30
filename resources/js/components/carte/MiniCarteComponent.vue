<template>
	<div class="h-100">
		<l-map
			ref="map"
			style="height: 100%; width: 100%;"
			:zoom="zoom"
			:center="center"
			:options="{zoomControl: false}"
			>

			<l-control-layers position="topright"></l-control-layers>
			<l-control-zoom position="topright"  ></l-control-zoom>
			<l-control position="bottomleft" >
				<button @click.prevent="recenterMap()" class="btn btn-primary btn-sm">
					<i class="fas fa-map-marker-alt"></i> Recentrer
				</button>
			</l-control>

			<!-- URL de la MAP -->
			<l-tile-layer
		     	v-for="tileProvider in tileProviders"
		    	:key="tileProvider.name"
		    	:name="tileProvider.name"
		    	:visible="tileProvider.visible"
		    	:url="tileProvider.url"
		    	:attribution="tileProvider.attribution"
		    	layer-type="base"
		    />

		    <l-marker v-if="checkIfUserCanDrag() === true"
		    :lat-lng="mare_position"
		    :icon="icon"
		    :draggable="true"
		    @dragend="dragEnd"
		    ></l-marker>

		    <l-marker v-else
		    :lat-lng="mare_position"
		    :icon="icon"
		    :draggable="false"
		    ></l-marker>

		</l-map>
	</div>
</template>

<script>
	import { LMap, LTileLayer, LControlLayers, LControlZoom, LControl, LMarker} from 'vue2-leaflet';
	import { latLng } from 'leaflet';
	import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster';
	import 'leaflet/dist/leaflet.css';

	const icon = L.icon({
		iconUrl:
			'https://s3-eu-west-1.amazonaws.com/ct-documents/emails/A-static.png',
		iconSize: [21, 31],
		iconAnchor: [10.5, 31],
		popupAnchor: [4, -25],
	});

	export default {
		props: ['mare', 'utilisateur', 'observateur'],
		components: {
			LMap,
			LTileLayer,
			LControlLayers,
			LControlZoom,
			LControl,
			LMarker
		},
		data(){
			return{
				icon,
				mare_position: [],
				mare_base_position: [],
				base_url: base_url,
				new_data_mare: [],

				isLoading: true,
				markers: [],
				markerClusterGroup: [],
				all_data: [],
				display_filters: true,
				base_url: base_url,
				zoom: 8,
				zoom_user: 0,
				center: [48.6833, 6.2],
				center_base: [48.6833, 6.2],
				fillColor: "green",
				departement_geo_json: [],
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
			    ],
			}
		},
		methods: {
			checkIfUserCanDrag: function(){
				let in_array = false;
				let observateur_id = this.observateur && this.observateur.id ? this.observateur.id : null;
				if(this.$user.authenticated === true){
					if(this.mare.utilisateur_id === this.utilisateur.id || this.$user.role === 'administrateur' || this.$user.role === 'gestionnaire'){
						in_array = true;
					}
					if(this.mare.observateur_id === observateur_id){
						in_array = true;
					}
					// else if(this.utilisateur.groupe_departements.length > 0){
					// 	in_array = this.utilisateur.groupe_departements.some(d => d === this.mare.departement_code);
					// }else if(this.utilisateur.groupe_intercommunalites.length > 0){
					// 	in_array = this.utilisateur.groupe_intercommunalites.some(i => i === this.mare.intercommunalite_siren);
					// }else if(this.utilisateur.groupe_communes.length > 0){
					// 	in_array = this.utilisateur.groupe_communes.some(c => c === this.mare.commune_insee);
					// }
					// else if(this.utilisateur.groupe_utilisateurs.length > 0){
					// 	in_array = this.utilisateur.groupe_utilisateurs.some(u => u === this.mare.utilisateur_id);
					// }
					return in_array;
				}
				return false;
			},
			saveNewInfoMare: function(){
				let self = this;
				let formData = new FormData();

				formData.append('departement_code', self.new_data_mare.dep);
				formData.append('geom', self.new_data_mare.geom);
				formData.append('commune_insee', self.new_data_mare.insee);
				formData.append('intercommunalite_siren', self.new_data_mare.siren);
				formData.append('x_l93', self.new_data_mare.x_lambert93);
				formData.append('y_l93', self.new_data_mare.y_lambert93);
				formData.append('lat', self.new_data_mare.lat);
				formData.append('lng', self.new_data_mare.lng);
				formData.append('mare_id', self.mare && self.mare.id ? self.mare.id : '');

				axios.post(base_url + '/mares/save_mare_after_drag', formData, { 
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
                    }
                })
                .catch(function (error) { 
                    self.isLoading = false;
                    return self.checkError(error); 
                });
			},
			dragEnd: function (e) {
				if(confirm('Etes-vous sûr de vouloir modifier la position de la mare ?')){
					let self = this;
					let formData = new FormData;
					let latLng = e.target.getLatLng();

					formData.append('latitude', latLng.lat);
					formData.append('longitude', latLng.lng);
					
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
	                        self.new_data_mare = response.data.infos;
	                        self.new_data_mare.lat = latLng.lat;
	                        self.new_data_mare.lng = latLng.lng;
	                        self.$emit('newInfoMareAfterDrag', self.new_data_mare);
	                        return self.saveNewInfoMare();
	                    }
	                })
	                .catch(function (error) { 
	                    self.isLoading = false;
	                    return self.checkError(error); 
	                });

					this.center = [48.6833, 6.2];
					this.mare_base_position = [48.6833, 6.2];
					this.$nextTick(() => {
				    	this.center = [latLng.lat, latLng.lng];
				    	this.mare_base_position = [latLng.lat, latLng.lng];
				    });
				}else{
					console.log(this.mare_base_position);
					this.mare_position = [48.6833, 6.2];
					return this.$nextTick(() => {
				    	this.mare_position = this.mare_base_position;
				    });
				}
			},
			recenterMap: function(){
				this.center = [48.6833, 6.2];
				this.$nextTick(() => {
			    	this.center = this.center_base;
			    });
			},
			updateZoomIfUserChange: function(event){
				this.zoom_user = event.target._animateToZoom;
			},
		},
		mounted(){
        	let self = this;
        	if(this.mare && this.mare.lat && this.mare.lng){
        		this.center = [this.mare.lat, this.mare.lng];
        		this.center_base = [this.mare.lat, this.mare.lng];
        		this.mare_position = [this.mare.lat, this.mare.lng];
        		this.mare_base_position = [this.mare.lat, this.mare.lng];

        		setTimeout(function(){
					self.zoom = 12;
				}, 700)
        	}
        }       
	}
</script>