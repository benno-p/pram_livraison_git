<template>
    <button class="btn btn-danger" @click="deleteData(id)">
        <i class="fas fa-trash-alt"></i>
    </button>
</template>

<script>
	export default{
		props: {
			idProps: Number,
			modelsProps: Array,
			modelNameProps: String,
			allDataProps: Object,
			confirmTextProps: String,
			urlProps: String,
		},
		data: function(){
			return{
				id : this.idProps,
				models : this.modelsProps,
				model_name : this.modelNameProps,
				confirm_text : this.confirmTextProps,
				url : this.urlProps,
			}
		},
		watch: {
			modelsProps: function(val){
				this.models = val;
			},
			idProps: function(val){
				this.id = val;
			}
		},
		methods: {
			deleteData: function(data_id){
				let self = this;
				let data = self.models.find(m => m.id == data_id);

				if(data){
					if(confirm(self.confirm_text)){
						axios.delete(base_url + '/' + self.url + '/' + data.id , {params: {'id': data.id}})
						.then((response) => {
                            if(response.data.error == false){
                                self.$emit('deleteData', data_id);
                                return self.showSuccess(response.data.message);
                            }else{
                                return self.showErrors(response.data.message);
                            }
                        }, (error) => {
                            return self.checkError(error);
                        });
					}
				}
            },
		}
	}


</script>