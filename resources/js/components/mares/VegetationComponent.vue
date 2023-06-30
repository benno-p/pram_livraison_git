<template>
	<div>
        <label for="nom" class="col-12 col-form-label text-cen-bleu pl-0">Recouvrement de la végétation herbacée sur la surface de la mare</span></label>
        <div class="form-group d-flex flex-wrap border">
        	<div class="image_helophytes">
            	<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_helophytes" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            	<img v-bind:src="base_url + '/vegetation/helophytes.png'" class="img-fluid"/>
            </div>
        	<div class="image_hydrophytes_enracines">
        		<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_hydrophytes" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        		<img v-bind:src="base_url + '/vegetation/hydrophytes_enracines.png'" class="img-fluid"/>
        	</div>
        	<div class="image_hydrophytes_non_enracines">
        		<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_hydrophytes_non_enracines" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        		<img v-bind:src="base_url + '/vegetation/hydrophytes_non_enracines.png'" class="img-fluid"/>
        	</div>
        	<div class="image_algues">
        		<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_algues" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        		<img v-bind:src="base_url + '/vegetation/algues.png'" class="img-fluid"/>
        	</div>
        	<div class="image_eau_libre">
        		<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_eau_libre" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        		<img v-bind:src="base_url + '/vegetation/eau_libre.png'" class="img-fluid"/>
        	</div>
        	<div class="image_fond_exonde">
        		<div class="input-group">
            		<input type="number" class="form-control" v-model="ecologie_fond_exonde" min="0" max="100" :disabled="disabled">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        		<img v-bind:src="base_url + '/vegetation/fond_exonde.png'" class="img-fluid"/>
        	</div>
        	<div>
        		<div class="input-group total_vegetation" v-if="!disabled">
            		<input type="text" class="form-control" readonly="readonly" :value="total_vegetation" min="0" max="100">
            		<div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
        	</div>
        </div>
	</div>
</template>


<script>
export default{
	props:{
		ecologieHelophytesProps: false,
		ecologieHydrophytesProps: false,
		ecologieHydrophytesNonEnracinesProps: false,
		ecologieAlguesProps: false,
		ecologieEauLibreProps: false,
		ecologieFondExondeProps: false,
		disabledProps : false,
	},
	data(){
		return{
			base_url: base_url,
			ecologie_helophytes: this.ecologieHelophytesProps ? this.ecologieHelophytesProps : null,
			ecologie_hydrophytes: this.ecologieHydrophytesProps ? this.ecologieHydrophytesProps : null,
			ecologie_hydrophytes_non_enracines: this.ecologieHydrophytesNonEnracinesProps ? this.ecologieHydrophytesNonEnracinesProps : null,
			ecologie_algues: this.ecologieAlguesProps ? this.ecologieAlguesProps : null,
			ecologie_eau_libre: this.ecologieEauLibreProps ? this.ecologieEauLibreProps : null,
			ecologie_fond_exonde: this.ecologieFondExondeProps ? this.ecologieFondExondeProps : null,
			disabled: this.disabledProps == true ? true : false,
			total_vegetation: '',
		}
	},
	methods:{
		calculTotalVegetation: function(){
			let total = Number(this.ecologie_helophytes) + Number(this.ecologie_hydrophytes) + Number(this.ecologie_hydrophytes_non_enracines) + Number(this.ecologie_algues) + Number(this.ecologie_eau_libre) + Number(this.ecologie_fond_exonde);

			if(total == 0){
				this.total_vegetation = '';
			}

			this.total_vegetation = total;
			return total;
		},
		resetOldVegetationTotal: function(new_total, old_total, model){
			if(new_total > 100){
				this.total_vegetation = old_total;
				this[model] = '';
			}
		},
		emitData: function(){
			let data = {
				ecologie_helophytes: this.ecologie_helophytes,
				ecologie_hydrophytes: this.ecologie_hydrophytes,
				ecologie_hydrophytes_non_enracines: this.ecologie_hydrophytes_non_enracines,
				ecologie_algues: this.ecologie_algues,
				ecologie_eau_libre: this.ecologie_eau_libre,
				ecologie_fond_exonde: this.ecologie_fond_exonde,
				total_vegetation: this.total_vegetation,
			}

			this.$emit('dataFromVegetationComponent', data);
		},
		checkModelForCalcul: function(model){
			let old_total = this.total_vegetation;
    		let new_total = this.calculTotalVegetation();
    		this.resetOldVegetationTotal(new_total, old_total, model);
    		this.emitData();
		}
	},
	watch: {
  		ecologie_helophytes: function(val){
  			this.checkModelForCalcul('ecologie_helophytes');
    	},
    	ecologie_hydrophytes: function(val){
    		this.checkModelForCalcul('ecologie_hydrophytes');
    	},
    	ecologie_hydrophytes_non_enracines: function(val){
    		this.checkModelForCalcul('ecologie_hydrophytes_non_enracines');
    	},
    	ecologie_algues: function(val){
    		this.checkModelForCalcul('ecologie_algues');
    	},
    	ecologie_eau_libre: function(val){
    		this.checkModelForCalcul('ecologie_eau_libre');
    	},
    	ecologie_fond_exonde: function(val){
    		this.checkModelForCalcul('ecologie_fond_exonde');
    	},
  	},
}
</script>