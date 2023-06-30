<template>	
	<div class="form-group row">
    	<div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Taxon</label>
            <div class="col-12" v-if="!disabled">
                <multiselect
                @input="searchEeeFauneByTaxon()"
                v-model="data.taxon_eee_faune" 
                :options="taxon_eee_faunes" 
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
                <input type="text" class="form-control" :value="data.taxon_eee_faune && data.taxon_eee_faune.nom ? data.taxon_eee_faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Faune</label>
            <div class="col-12" v-if="!disabled">
                <multiselect 
                :disabled="data.taxon_eee_faune.length == 0"
                v-model="data.eee_faune" 
                :options="eee_faunes" 
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
                <input type="text" class="form-control" :value="data.eee_faune && data.eee_faune.nom ? data.eee_faune.nom : '' " readonly="readonly">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">Nombre</label>
            <div  class="col-6">
                <input type="number" class="form-control text-right" v-model="data.nombre" :disabled="data.eee_faune.length == 0 || disabled == true">
            </div>
        </div>
        <div class="col-12 col-md-2" v-if="!disabled">
            <label for="nom" class="col-12 col-form-label text-cen-bleu">&nbsp;</label>
            <div class="col-6">
                <button class="btn btn-danger" @click.prevent="deleteEeeFauneComponent()">
                	<i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
	export default{
		props:{
			taxonEeeFaunesProps: Array,
			idEeeFauneComponentProps: Number,
            dataProps: false,
            disabledProps: false,
		},
		watch:{
			data: {
	            handler(newVal, oldVal) {
	                this.$emit('dataFromEeeFauneComponent', this.data);
	            },
	            deep: true,
	        }
		},
		data(){
			return{
				taxon_eee_faunes: this.taxonEeeFaunesProps,
				eee_faunes: [],
                disabled: this.disabledProps,
				data: {
					id_eee_faune_component: this.idEeeFauneComponentProps,
					taxon_eee_faune: this.dataProps && this.dataProps.taxon_eee_faune ? this.dataProps.taxon_eee_faune : [],
					eee_faune: this.dataProps && this.dataProps.eee_faune ? this.dataProps.eee_faune : [],
					nombre: this.dataProps && this.dataProps.nombre ? this.dataProps.nombre : '',
				}
			}
		},
		methods:{
			searchEeeFauneByTaxon: function(){
				this.eee_faunes = this.data.taxon_eee_faune && this.data.taxon_eee_faune.eee_faunes ? this.data.taxon_eee_faune.eee_faunes : [];
			},
			deleteEeeFauneComponent: function(){
				this.$emit('deleteEeeFauneComponent', this.data.id_eee_faune_component);
				this.$destroy();
			}
		}
	}
</script>