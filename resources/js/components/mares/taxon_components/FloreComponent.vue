<template>	
	<div class="form-group row">
    	<div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Taxon</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                @input="searchFloreByTaxon()"
                v-model="data.taxon_flore" 
                :options="taxon_flores" 
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
                <input type="text" class="form-control" :value="data.taxon_flore && data.taxon_flore.nom ? data.taxon_flore.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Flore</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                :disabled="data.taxon_flore.length == 0"
                v-model="data.flore" 
                :options="flores" 
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
                <input type="text" class="form-control" :value="data.flore && data.flore.nom ? data.flore.nom : '' " readonly="readonly">
            </div>
        </div>

        <div class="col-12 col-md-2 offset-md-4" v-if="!disabled">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">&nbsp;</label>
            <div class="col-6">
                <button class="btn btn-danger" @click.prevent="deleteFloreComponent()">
                	<i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
	export default{
		props:{
			taxonFloresProps: Array,
			idFloreComponentProps: Number,
            dataProps: false,
            disabledProps: false,
		},
		watch:{
			data: {
	            handler(newVal, oldVal) {
	                this.$emit('dataFromFloreComponent', this.data);
	            },
	            deep: true,
	        }
		},
		data(){
			return{
				taxon_flores: this.taxonFloresProps,
				flores: [],
                disabled: this.disabledProps,
				data: {
					id_flore_component: this.idFloreComponentProps,
					taxon_flore: this.dataProps && this.dataProps.taxon_flore ? this.dataProps.taxon_flore : [],
					flore: this.dataProps && this.dataProps.flore ? this.dataProps.flore : [],	
				}
			}
		},
		methods:{
			searchFloreByTaxon: function(){
				this.flores = this.data.taxon_flore && this.data.taxon_flore.flores ? this.data.taxon_flore.flores : [];
			},
			deleteFloreComponent: function(){
				this.$emit('deleteFloreComponent', this.data.id_flore_component);
				this.$destroy();
			}
		}
	}
</script>