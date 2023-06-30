<template>	
	<div class="form-group row">
    	<div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Taxon</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                @input="searchEeeFloreByTaxon()"
                v-model="data.taxon_eee_flore" 
                :options="taxon_eee_flores" 
                placeholder="Sélection" 
                label="nom" 
                track-by="id"
                selectLabel=""
                deselectLabel=""
                selectedLabel="Selectionner"
                >
                </multiselect>
            </div>
            <div class="col-12" v-else>
                <input type="text" class="form-control" :value="data.taxon_eee_flore && data.taxon_eee_flore.nom ? data.taxon_eee_flore.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Flore</label>
            <div class="col-12" v-if="!disabled">
                <multiselect 
                :disabled="data.taxon_eee_flore.length == 0"
                v-model="data.eee_flore" 
                :options="eee_flores" 
                placeholder="Sélection" 
                label="nom" 
                track-by="id"
                selectLabel=""
                deselectLabel=""
                selectedLabel="Selectionner"
                >
                </multiselect>
            </div>
            <div class="col-12" v-else>
                <input type="text" class="form-control" :value="data.eee_flore&& data.eee_flore.nom ? data.eee_flore.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Pourcentage de la surface de la mare colonisée</label>
            <div class="col-6 input-group">
                <input type="number" class="form-control text-right" v-model="data.pourcentage" :disabled="data.eee_flore.length == 0 || disabled == true">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-2" v-if="!disabled">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">&nbsp;</label>
            <div class="col-6">
                <button class="btn btn-danger" @click.prevent="deleteEeeFloreComponent()">
                	<i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
	export default{
		props:{
			taxonEeeFloresProps: Array,
			idEeeFloreComponentProps: Number,
            dataProps: false,
            disabledProps: false,
		},
		watch:{
			data: {
	            handler(newVal, oldVal) {
	                this.$emit('dataFromEeeFloreComponent', this.data);
	            },
	            deep: true,
	        }
		},
		data(){
			return{
				taxon_eee_flores: this.taxonEeeFloresProps,
				eee_flores: [],
                disabled: this.disabledProps,
				data: {
					id_eee_flore_component: this.idEeeFloreComponentProps,
					taxon_eee_flore: this.dataProps && this.dataProps.taxon_eee_flore ? this.dataProps.taxon_eee_flore : [],
					eee_flore: this.dataProps && this.dataProps.eee_flore ? this.dataProps.eee_flore : [],
					pourcentage: this.dataProps && this.dataProps.pourcentage ? this.dataProps.pourcentage : '',	
				}
			}
		},
		methods:{
			searchEeeFloreByTaxon: function(){
				this.eee_flores = this.data.taxon_eee_flore && this.data.taxon_eee_flore.eee_flores ? this.data.taxon_eee_flore.eee_flores : [];
			},
			deleteEeeFloreComponent: function(){
				this.$emit('deleteEeeFloreComponent', this.data.id_eee_flore_component);
				this.$destroy();
			}
		}
	}
</script>