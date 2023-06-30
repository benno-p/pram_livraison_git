<template>
    <div>
        <div class="row">
           <div class="col-12">
                <div class="w-100">
                    <!-- <a href="https://www.pram-grandest.fr/ "> -->
                    <a :href="$config.redirect_url_logo ? $config.redirect_url_logo : 'https://www.pram-grandest.fr/'" target="_blank">
                        <img :src="$config.logo ? base_url + '/configuration/logo/' + $config.logo : base_url + '/images/logo/logo.png'" class="rounded mx-auto d-block mb-5" alt="...">
                    </a>
                    <div class="card border-0 bg-transparent">
                        <div class="card-header bg-transparent">
                            <div class="d-flex flex-wrap justify-content-between">
                                <h2 class="col-12 text-center" style="align-self: center;">
                                    <span v-html="$config.titre_accueil"></span>
                                </h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <span v-html="$config.texte_accueil"></span>
                            <div class="d-flex justify-content-around flex-wrap" v-if="$user.authenticated == false">
                                <router-link to="/carte" class="btn btn-primary mt-2">
                                    <i class="fas fa-eye"></i> Consulter
                                </router-link>
                                <router-link to="/connexion" class="btn btn-success mt-2">
                                    <i class="fas fa-unlock-alt"></i> Se connecter
                                </router-link>
                                <router-link to="/demande_compte" class="btn btn-cen-orange mt-2">
                                    <i class="fas fa-sign-in-alt"></i> Demander la création d'un compte
                                </router-link>
                            </div>
                            <div class="d-flex justify-content-around" v-else>
                                <router-link to="/carte" class="btn btn-primary mt-2">
                                    <i class="fas fa-eye"></i> Consulter, saisir ou modifier des mares
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row mt-5">
             <div class="col-12 bg-white">
                 <div class="row">
                    <div class="col-md-3 align-self-center" v-for="partenaire in $config.partenaires">
                        <div class="card p-3 border-0">
                            <a :href="partenaire.site_url ? partenaire.site_url : '#'">
                                <img :src="base_url + '/configuration/logo_partenaires/'+partenaire.logo" class="img-fluid">
                            </a>
                        </div>
                    </div>
<!--                     <div class="col-md-3 align-self-center">
                        <div class="card p-3 border-0">
                            <a href="http://www.grand-est.developpement-durable.gouv.fr/"><img :src="base_url + '/images/partenaires/dreal_ge.png'" class="img-fluid"></a>
                        </div>
                    </div>
                    <div class="col-md-3 align-self-center">
                        <div class="card p-3 border-0">
                            <a href="https://www.grandest.fr/"><img :src="base_url + '/images/partenaires/region_ge.jpg'" class="img-fluid"></a>
                        </div>
                    </div>
                    <div class="col-md-3 align-self-center">
                        <div class="card p-3 border-0">
                            <a href="https://www.eau-rhin-meuse.fr/"><img :src="base_url + '/images/partenaires/aerm.png'" class="img-fluid"></a>
                        </div>
                    </div>
                    <div class="col-md-3 align-self-center">
                        <div class="card p-3 border-0">
                            <a href="http://www.eau-seine-normandie.fr/"><img :src="base_url + '/images/partenaires/aesn.png'" class="img-fluid"></a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default{
    data(){
        return{
            base_url: base_url,
        }
    },
}


</script>