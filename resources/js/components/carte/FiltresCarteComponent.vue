<!-- Card filtre -->
<template>
	<div class="w-100" id="bloc_filtre">
		<div class="col-12 col-md-3 ml-auto p-0" style="z-index: 1111" id="filtre_carte_component">
			<div class="card mt-4">
				<div class="card-header">
<!-- 					<button class="btn btn-danger float-right btn-sm ml-2" @click.prevent="locateMe()" v-if="$user.role === 'developpeur'">
						<i class="fas fa-map-marker-alt"></i>
						<span>Me géolocaliser</span>
					</button> -->
					<button class="btn btn-secondary float-right btn-sm" @click="reinitialiseFiltre()">
						<i class="fas fa-sync"></i> Réinitialiser
					</button>
					Filtres
				</div>
				<div class="card-body">
					<div class="col-12 mb-4">
						<multiselect 
						v-model="departement"
						:options="departements" 
						deselectLabel="" 
						track-by="id" 
						label="nom"
						placeholder="Département"
						selectLabel=""
						:searchable="true" 
						:allow-empty="false"
						>
						</multiselect>
					</div>

					<div class="col-12 mb-4">
						<multiselect
						:disabled="!departement"
						v-model="intercommunalite"
						:options="intercommunalites" 
						deselectLabel="" 
						track-by="id" 
						label="raison_soc"
						placeholder="Intercommunalités"
						selectLabel=""
						:searchable="true" 
						:allow-empty="false"
						>
						</multiselect>
					</div>

					<div class="col-12 mb-4">
						<multiselect
						:disabled="!departement"
						v-model="commune"
						:options="communes" 
						deselectLabel="" 
						track-by="id" 
						label="nom"
						placeholder="Communes"
						selectLabel=""
						:searchable="true" 
						:allow-empty="false"
						>
						</multiselect>
					</div>

					<div class="col-12 mb-2">
						<multiselect
						:disabled="commune === ''"
						v-model="mare"
						:options="mares" 
						deselectLabel="" 
						track-by="id" 
						label="id"
						placeholder="Mares"
						selectLabel=""
						:searchable="true" 
						:allow-empty="false"
						>
						</multiselect>
					</div>
				</div>

				<div class="col-12 d-flex flex-wrap">
					<ul class="list-unstyled pl-4">
						<li class="li_legende mb-2">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="show_mare_caracterise" v-model="show_mare_caracterise" @change="changeShowMareFilter()">
								<label class="custom-control-label" for="show_mare_caracterise"></label>
							</div>
							<span class="dot mr-2 bg-primary"></span>
							<span class="li_legende_text">Mare Caractérisée</span>
						</li>
						<li class="li_legende mb-2">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="show_mare_vue" v-model="show_mare_vue" @change="changeShowMareFilter()">
								<label class="custom-control-label" for="show_mare_vue"></label>
							</div>
							<span class="dot mr-2 bg-success"></span>
							<span class="li_legende_text">Mare Vue</span>
						</li>
					</ul>
					<ul class="list-unstyled pl-3">
						<li class="li_legende mb-2">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="show_mare_potentielle" v-model="show_mare_potentielle" @change="changeShowMareFilter()">
								<label class="custom-control-label" for="show_mare_potentielle"></label>
							</div>
							<span class="dot mr-2 bg-danger"></span>
							<span class="li_legende_text">Mare Potentielle</span>
						</li>
						<li class="li_legende mb-2">
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="show_mare_disparue" v-model="show_mare_disparue" @change="changeShowMareFilter()">
								<label class="custom-control-label" for="show_mare_disparue"></label>
							</div>
							<span class="dot mr-2 bg-dark"></span>
							<span class="li_legende_text">Mare Disparue</span>
						</li>
					</ul>
				</div>

				<div class="col-12 pl-4">
					<div class="pl-3 pb-4">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="show_all_mare">
							<label class="custom-control-label" for="customSwitch1">Voir toutes les mares ({{total_toutes_mares}})</label>
						</div>
						<div class="text-secondary mt-2">
							Votre recherche : {{nbMaresByFilters}} mare(s)
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="d-flex">
			<button class="btn btn-cen-orange col-6" style="z-index: 9999" id="bouton_filtre_carte" @click="displayFiltres()">
				<i class="fas fa-filter"></i>
				<span v-if="!displayFilter">Voir les filtres</span>
				<span v-else>Masquer les filtres</span>
			</button>
		</div>
	</div>
</template>

<script>
export default{
	props: {
		departementsProps: Array,
		intercommunalitesProps: Array,
		communesProps: Array,
		maresProps: Array,
		totalMaresProps: Number,
		totalToutesMaresProps: Number,
		nbMaresByFilters: Number,
	},
	data(){
		return{
			departements: Object.values(this.departementsProps),
			intercommunalites: this.intercommunalitesProps,
			communes: this.communesProps,
			mares: this.maresProps,
			total_mares: this.totalMaresProps,
			total_toutes_mares: this.totalToutesMaresProps,

			displayFilter: false,
			departement: '',
			intercommunalite: '',
			commune: '',
			mare: '',
			show_all_mare: false,
			show_mare_caracterise: true,
			show_mare_vue: true,
			show_mare_potentielle: true,
			show_mare_disparue: true,
		}
	},
	watch : {
		show_all_mare: function(val){
			return this.$emit('loadAllMares', val);
		},
		departement: function(val){
			this.intercommunalite = '';
			this.commune = '';
			this.mare = '';
			return this.$emit('departementChange', this.departement && this.departement.id ? this.departement.id : '');
		},
		intercommunalite: function(val){
			this.commune = '';
			this.mare = '';
			return this.$emit('intercommunaliteChange', this.intercommunalite && this.intercommunalite.id ? this.intercommunalite.id : '');
		},
		commune: function(val){
			this.mare = '';
			return this.$emit('communeChange', this.commune && this.commune.id ? this.commune.id : '');
		},
		mare: function(val){
			return this.$emit('mareChange', this.mare && this.mare.id ? this.mare.id : '');
		},
		departementsProps: function(val){
			this.departements = val;
		},
		intercommunalitesProps: function(val){
			this.intercommunalites = val;
		},
		communesProps: function(val){
			this.communes = val;
		},
		maresProps: function(val){
			this.mares = val;
		},
		totalMaresProps: function(val){
			this.total_mares = val;
		}
	},
	methods: {
		changeShowMareFilter(){
			let self = this;
			let data = {
				show_mare_caracterise: self.show_mare_caracterise,
				show_mare_vue: self.show_mare_vue,
				show_mare_potentielle: self.show_mare_potentielle,
				show_mare_disparue: self.show_mare_disparue,
			}
			self.$emit('changeShowMareFilter', data);
		},
		displayFiltres: function(){
			let div_filtre = document.getElementById('filtre_carte_component');

			if(div_filtre.style.display == 'block'){
				this.displayFilter = false;
				div_filtre.style.display = 'none';
			}else{
				this.displayFilter = true;
				div_filtre.style.display = 'block';
			}
		},
		reinitialiseFiltre: function(){
			this.intercommunalites = [];
			this.mares = [];
			this.communes = [];
			this.departement = '';
			this.intercommunalite = '';
			this.commune = '';
			this.mare = '';
			this.show_mare_caracterise = true;
			this.show_mare_vue = true;
			this.show_mare_potentielle = true;
			this.show_mare_disparue = true;
			// this.nb_mares_by_filters = 0;
			this.changeShowMareFilter();
			return this.$emit('reinitialiseFiltre');
		},
	},
}
</script>

<style>
	.dot {
		height: 15px;
		width: 15px;
		background-color: #bbb;
		border-radius: 50%;
		display: inline-block;
	}
	.li_legende{
		display: flex;
		align-items: center
	}
	.li_legende_text{
		font-size: 0.9em;
	}
	.multiselect--disabled .multiselect__tags{
		background-color: #ededed!important;
	}

	.custom-switch .custom-control-label::before { cursor: pointer; }
	.custom-switch .custom-control-label::after { cursor: pointer; }
	.custom-switch label:hover{ cursor: pointer; }
</style>