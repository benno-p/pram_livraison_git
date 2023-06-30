<template>
    <div class="position-relative" ref="container">
        <entete-layout :isLoading="false"></entete-layout>

        <b-container fluid class="mt--7" style="min-height: 60vh!important;">
	        <div class="card">
	            <div class="card-body row">
	            	<div class="col-12 col-md-4 mt-4">
	            		<div class="card h-100">
				            <div class="card-header">
				                <h5> <i class="fas fa-tasks"></i> Taxons</h5>
				            </div>
				            <div class="card-body">
				            	<div class="col-12 m-2">
					            	<router-link to="/taxon_faunes">
	                                    <i class="fas fa-angle-right"></i> Taxon Faune
	                                </router-link>
		                        </div>
				            	<div class="col-12 m-2">
					            	<router-link to="/taxon_flores">
	                                    <i class="fas fa-angle-right"></i> Taxon Flore
	                                </router-link>
		                        </div>
				            	<div class="col-12 m-2">
					            	<router-link to="/taxon_eee_faunes">
	                                    <i class="fas fa-angle-right"></i> Taxon EEE Faunes
	                                </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/taxon_eee_flores">
	                                    <i class="fas fa-angle-right"></i> Taxon EEE Flores
	                                </router-link>
		                        </div>
		                        
				            </div>
				        </div>
	            	</div>


	            	<div class="col-12 col-md-4 mt-4">
	            		<div class="card h-100">
				            <div class="card-header">
				                <h5><i class="fas fa-otter"></i> Faunes, Flores et EEE</h5>
				            </div>
				            <div class="card-body">
				            	<div class="col-12 m-2">
					            	<router-link to="/faunes">
		                                <i class="fas fa-angle-right"></i> Faunes
		                            </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/flores">
	                                    <i class="fas fa-angle-right"></i> Flores
	                                </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/eee_faunes">
	                                    <i class="fas fa-angle-right"></i> EEE Faune
	                                </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/eee_flores">
	                                    <i class="fas fa-angle-right"></i> EEE Flore
	                                </router-link>
		                        </div>
				            </div>
				        </div>
	            	</div>

	            	<div class="col-12 col-md-4 mt-4" v-if="authorisations('access_modification_formulaire')">
	            		<div class="card h-100">
				            <div class="card-header">
				                <h5><i class="fas fa-ellipsis-h"></i> Autres</h5>
				            </div>
				            <div class="card-body">
				            	<div class="col-12 m-2">
					            	<router-link to="/formulaires">
		                                <i class="fas fa-angle-right"></i> Formulaire
		                            </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/observateurs">
		                                <i class="fas fa-angle-right"></i> Observateurs
		                            </router-link>
		                        </div>
				            </div>
				        </div>
	            	</div>

	            	<div class="col-12 col-md-4 mt-4" v-if="authorisations('access_config')">
	            		<div class="card h-100">
				            <div class="card-header">
				                <h5><i class="fas fa-tools"></i> Configuration de l'application</h5>
				            </div>
				            <div class="card-body">
				            	<div class="col-12 m-2">
					            	<router-link to="/configuration_generales">
		                                <i class="fas fa-angle-right"></i> Configuration générale
		                            </router-link>
		                        </div>
		                        <div class="col-12 m-2">
					            	<router-link to="/configuration_logo_partenaires">
		                                <i class="fas fa-angle-right"></i> Configuration logo partenaires
		                            </router-link>
		                        </div>
				            </div>
				        </div>
	            	</div>

	            </div>
	        </div>
	    </b-container>
    </div>
</template>