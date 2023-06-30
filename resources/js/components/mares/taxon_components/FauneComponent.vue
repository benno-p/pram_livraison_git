<template>	
	<div class="form-group row">
    	<div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Taxon</label>
            <div class="col-12" v-if="!disabled">
                <multiselect 
                @input="searchFauneByTaxon()"
                v-model="data.taxon_faune" 
                :options="taxon_faunes" 
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
                <input type="text" class="form-control" :value="data.taxon_faune && data.taxon_faune.nom ? data.taxon_faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Faune</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                :disabled="data.taxon_faune.length == 0"
                v-model="data.faune" 
                :options="faunes" 
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
                <input type="text" class="form-control" :value="data.faune && data.faune.nom ? data.faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Nombre</label>
            <div class="col-6">
                <input type="number" class="form-control text-right" v-model="data.nombre" :disabled="data.faune.length == 0 || disabled == true">
            </div>
        </div>
        <div class="col-12 col-md-2" v-if="!disabled">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">&nbsp;</label>
            <div class="col-6">
                <button class="btn btn-danger" @click.prevent="deleteFauneComponent()">
                	<i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
	export default{
		props:{
			taxonFaunesProps: Array,
			idFauneComponentProps: Number,
            dataProps: false,
            disabledProps: false,
		},
		watch:{
			data: {
	            handler(newVal, oldVal) {
	                this.$emit('dataFromFauneComponent', this.data);
	            },
	            deep: true,
	        }
		},
		data(){
			return{
				taxon_faunes: this.taxonFaunesProps,
				faunes: [],
                disabled: this.disabledProps,
				data: {
					id_faune_component: this.idFauneComponentProps,
					taxon_faune: this.dataProps && this.dataProps.taxon_faune ? this.dataProps.taxon_faune : [],
					faune: this.dataProps && this.dataProps.faune ? this.dataProps.faune : [],
					nombre: this.dataProps && this.dataProps.nombre ? this.dataProps.nombre : '',	
				}
			}
		},
		methods:{
			searchFauneByTaxon: function(){
				this.faunes = this.data.taxon_faune && this.data.taxon_faune.faunes ? this.data.taxon_faune.faunes : [];
			},
			deleteFauneComponent: function(){
				this.$emit('deleteFauneComponent', this.data.id_faune_component);
				this.$destroy();
			}
		},
	}
</script>