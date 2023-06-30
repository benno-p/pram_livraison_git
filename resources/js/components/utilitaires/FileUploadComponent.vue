<template>
	<div>
		<template v-if="copy_fichier_exist == ''">
			<div class="form-group row">
		        <label class="col-12 col-md-2 col-form-label">{{titre}}<span class="text-danger" v-if="required">*</span></label>
		        <div class="custom-file col-12 col-md-10">
		            <input type="file" class="custom-file-input" ref="fichier" @change="handleFileUpload()" :accept="accept">
		            <label class="custom-file-label mr-3 ml-3" ref="label_fichier">
		                {{label}}
		            </label>
		        </div>
		    </div>

		    <div class="form-group row" v-if="fichier_url != ''">
		        <label for="nom" class="col-12 col-md-2 col-form-label">Prévisualisation</label>
		        <div class="col-12 col-md-4 align-self-center">
		            <img :src="fichier_url" class="img-fluid" v-if="file_upload_is_image"/>
		            <div v-else>
		            	<a :href="fichier_url" target="_blank"><i class="fas fa-link"></i> Fichier</a>
		            </div>
		        </div>
		    </div>
		</template>
		<template v-else>
			<div class="form-group row" v-if="fichier_url != ''">
		        <label for="nom" class="col-12 col-md-2 col-form-label">Prévisualisation</label>
		        <div class="col-12 col-md-4 align-self-center">
		            <img :src="fichier_url" class="img-fluid" v-if="file_upload_is_image"/>
		            <div v-else>
		            	<a :href="fichier_url" target="_blank"><i class="fas fa-link"></i> Fichier</a>
		            </div>
		        </div>

		        <button class="btn btn-warning height-fit-content" @click.prevent="modifierFichier()" v-if="!display_modifier_fichier && titre != 'Fichier'">
			    	<i class="fas fa-edit"></i> Modifier
			    </button>

			    <button class="btn btn-danger height-fit-content" @click.prevent="supprimerFichier()" v-if="!display_modifier_fichier && titre != 'Fichier'">
			    	<i class="fas fa-trash-alt"></i> Supprimer
			    </button>

			    <template v-if="display_modifier_fichier">
			        <div class="custom-file col-12 col-md-6">
			            <input type="file" class="custom-file-input" ref="fichier" @change="handleFileUploadModifier()" :accept="accept">
			            <label class="custom-file-label mr-3 ml-3" ref="label_fichier">
			                {{label}}
			            </label>

			            <button class="btn btn-danger mt-4" @click="annulerModification()">
				        	<i class="fas fa-undo-alt"></i> Annuler
				        </button>
			        </div>
			    </template>

		    </div>
		</template>
	</div>
</template>

<script>
	export default{
		props: ['titre', 'accept', 'label', 'fichier_exist', 'fichierFromBloc', 'idUploadFileComponent', 'required'],
		data(){
			return{
				fichier: [],
				fichier_url: '',
				display_modifier_fichier: false,
				modifier_fichier: false,
				copy_fichier_exist: '',
				file_upload_is_image: false,
				file_upload_is_file: false,
				id_upload_file_component: this.idUploadFileComponent,
			}
		},
		methods: {
			supprimerFichier: function(){
				this.fichier = [];
				this.fichier_url = '';
				this.copy_fichier_exist = '';
				this.modifier_fichier = true;
				this.emit();
			},
			modifierFichier: function(){
				this.display_modifier_fichier = true;
			},
			annulerModification: function(){
				this.display_modifier_fichier = false;
				this.modifier_fichier = false;
				this.fichier = [];
				this.fichier_url = this.fichier_exist;
				this.emit();
			},
			handleFileUpload: function(){
				this.file_upload_is_file = false;
				this.file_upload_is_image = false;
                this.fichier = this.$refs.fichier.files[0];
                this.modifier_fichier = false;

                if(this.fichier.name){
                	this.checkIfFileOrImage(this.fichier.name);
                	// if(this.fichier.name.split('.').pop() === 'pdf'){
                	// 	this.file_upload_is_file = true;
                	// }else{
                	// 	this.file_upload_is_image = true;
                	// }
                	// console.log(this.fichier.name.split('.').pop());
                }

                // return this.fichier.split('.').pop();

                this.emit();
            },
            checkIfFileOrImage: function(nom_fichier){
            	if(this.objectSize(nom_fichier) > 0 && nom_fichier.split('.').pop() === 'json' || this.objectSize(nom_fichier) > 0 && nom_fichier.split('.').pop() === 'geojson'){
            		console.log('la')
            		this.file_upload_is_file = true;
            	}
            	else if(this.objectSize(nom_fichier) > 0 && nom_fichier.split('.').pop() === 'pdf'){
            		this.file_upload_is_file = true;
            	}else{
            		this.file_upload_is_image = true;
            	}
            },
            handleFileUploadModifier: function(){
            	this.fichier = this.$refs.fichier.files[0];
            	this.modifier_fichier = true;

            	if(this.fichier.name){
                	this.checkIfFileOrImage(this.fichier.name);
                }

            	this.emit();
            },
            emit: function(){
            	let event = {
            		fichier : this.fichier,
            		modifier_fichier: this.modifier_fichier,
            		idUploadFileComponent: this.id_upload_file_component
            	}

            	this.$emit('fichierFromFileUploadComponent', event);

                if(this.fichier.name){
                    this.fichier_url = URL.createObjectURL(this.fichier);
                    this.$refs.label_fichier.innerHTML = this.fichier.name;
                }
            }
		},
		mounted(){
			if(this.fichier_exist){
				this.copy_fichier_exist = this.fichier_exist;
				this.fichier_url = this.fichier_exist;
				this.checkIfFileOrImage(this.fichier_url);
			}

			if(this.fichierFromBloc && typeof this.fichierFromBloc.name == 'string'){
				this.fichier = this.fichierFromBloc;
				this.fichier_url = URL.createObjectURL(this.fichier);
				this.$refs.label_fichier.innerHTML = this.fichier.name;
			}
		}
	}

</script>