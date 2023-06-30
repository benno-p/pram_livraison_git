<template>
	<div class="card mb-4 mt-4">
            <div class="">
                <div class="col-12 p-4 widget-chart d-flex flex-wrap justify-content-center">
                    <router-link :to="'/carte'" class="btn btn-primary mt-2">
                        <i class="fas fa-water"></i> Créer une mare depuis la carte
                    </router-link>
                    <router-link :to="'/carte'" class="btn btn-orange mt-2">
                    	<i class="fas fa-file-alt"></i> Créer une fiche depuis la carte
                    </router-link>
                    <router-link :to="'/recherche'" class="btn btn-success mt-2">
                    	<i class="fas fa-search"></i> Créer une fiche depuis un identifiant de mare
                    </router-link>

                    <a :href="base_url+'/fichiers/fiche_mare.pdf'" class="btn btn-info text-white mt-2" download>
                        <i class="fas fa-download"></i> Télécharger la fiche mare de terrain
                    </a>

                    </a>
                    <!-- Créer une mare par la carte, -->
                    <!-- créer une fiche pas la carte, -->
                    <!-- créer une fiche pas identifiant de mare -->
                </div>
            </div>
        </div>
</template>

<script>
export default{
    data(){
        return{
            base_url: base_url
        }
    }
}

</script>