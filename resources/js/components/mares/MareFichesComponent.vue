<template>
	<div>
	    <b-tabs content-class="" style="min-height: 50vh;">
	        <b-tab active v-for="(fiche,index) in mare.fiches" :key="fiche.id">
	            <template slot='title'>
	                <span class="text-danger" v-if="fiche.statut && fiche.statut.nom_interne && fiche.statut.nom_interne === 'en_attente_de_validation'">
	                	<i class="fas fa-paste"></i> Fiche n°{{Number(index) + 1}} (En attente de validation)
	                </span>
	                <span v-else>
	                	<i class="fas fa-paste"></i> Fiche n°{{Number(index) + 1}}
	                	<!-- <button class="btn btn-success btn-sm" @click="exportFiche(fiche.id)">
	                		<i class="fas fa-file-pdf"></i> 
	                	</button> -->
	                	 <!-- Fiche n°{{Number(index) + 1}} -->
	                </span>
	            </template>
	            <div class="card p-4">
	                <mare-show-component
	                :fiche="fiche"
	                :type_mares="type_mares"
					:stade_evolution_mares="stade_evolution_mares"
					:usage_mares="usage_mares"
					:usage_dechets="usage_dechets"
					:situation_contextes="situation_contextes"
					:situation_batis="situation_batis"
					:situation_clotures="situation_clotures"
					:caracteristique_formes="caracteristique_formes"
					:caracteristique_eau_hauteurs="caracteristique_eau_hauteurs"
					:caracteristique_fond_mares="caracteristique_fond_mares"
					:caracteristique_berges="caracteristique_berges"
					:caracteristique_pietinements="caracteristique_pietinements"
					:hydrologie_regimes="hydrologie_regimes"
					:hydrologie_reseaux="hydrologie_reseaux"
					:hydrologie_alimentations="hydrologie_alimentations"
					:hydrologie_turbidites="hydrologie_turbidites"
					:hydrologie_tampons="hydrologie_tampons"
					:hydrologie_exutoires="hydrologie_exutoires"
					:hydrologie_presence_poissons="hydrologie_presence_poissons"
					:ecologie_boisements="ecologie_boisements"
					:ecologie_ombrages="ecologie_ombrages"
					:interventions="interventions"
					:taxon_faunes="taxon_faunes"
					:taxon_flores="taxon_flores"
					:taxon_eee_faunes="taxon_eee_faunes"
					:taxon_eee_flores="taxon_eee_flores"
					:caracterisations="caracterisations"
	                >
	                </mare-show-component>
	            </div>
	        </b-tab>
	    </b-tabs>
	</div>
</template>

<script>
export default{
	props:[
		'mare',
		'type_mares',
		'stade_evolution_mares',
		'usage_mares',
		'usage_dechets',
		'situation_contextes',
		'situation_batis',
		'situation_clotures',
		'caracteristique_formes',
		'caracteristique_eau_hauteurs',
		'caracteristique_fond_mares',
		'caracteristique_berges',
		'caracteristique_pietinements',
		'hydrologie_regimes',
		'hydrologie_reseaux',
		'hydrologie_alimentations',
		'hydrologie_turbidites',
		'hydrologie_tampons',
		'hydrologie_exutoires',
		'hydrologie_presence_poissons',
		'ecologie_boisements',
		'ecologie_ombrages',
		'interventions',
		'taxon_faunes',
		'taxon_flores',
		'taxon_eee_faunes',
		'taxon_eee_flores',
		'caracterisations'
	],
	data: function(){
		return {
			tabIndex: 0,
			base_url: base_url
		}
	},
	methods:{
		exportFiche(id){
			let self = this;
            axios.get(self.base_url + '/exports/fiche_pdf/'+id)
            .then(function (resp) {
                console.log(resp.data);
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
		}
	}
}


</script>