<template>
    <div class="position-relative">
        <base-header class="pb-6 pb-8 pt-8 pt-md-7 bg-gradient-success" v-if="$user.authenticated == true">
            <b-row>
                <b-col xl="12" md="12">
                    <stats-card type="gradient-red" :sub-title="'Bonjour '+$user.prenom" class="mb-4">
                    </stats-card>
                </b-col>
            </b-row>
        </base-header>

        <is-mobile ref="isMobileComponent" v-on:detectMobile="detectMobile"></is-mobile>
        <div class="vld-parent">
            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
        </div>

        <div :class="$user.authenticated == true ? 'mt--9 min-height-perso container-fluid' : 'container-fluid-perso'">
            <div class="row" v-bind:style="{ height: computedHeight }" v-if="$user.authenticated == false">
                <div class="col-md-6 text-center py-5 d-flex flex-column justify-content-center auth-bg-section text-white" 
                :style="$config.image_page_accueil ? { background: 'linear-gradient(0deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)),center / cover url(configuration/image_page_accueil/'+$config.image_page_accueil+')' } : { background: 'linear-gradient(0deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)),center / cover url(images/login.jpg)' } ">

                    <!-- <h1 class="text-reset">Application cartographique <br>du PRAM Grand Est</h1> -->
                    <span v-html="$config.titre_image_accueil" class="text-reset"></span>
                </div>
                <div class="col-md-6 text-center d-flex flex-column pt-5 justify-content-center">
                    <transition name="component-fade" mode="out-in">
                        <router-view
                            name="second"
                        ></router-view>
                    </transition>
                </div>
            </div>

            <div v-else-if="$user.authenticated == true" class="w-100">
                <h2>Bonjour {{$user.prenom}}</h2>
                <div v-if="$user.role == 'developpeur'" class="col-12 p-0">
                    <developpeur-dashboard></developpeur-dashboard>
                </div>
                <div  v-else-if="$user.role == 'administrateur'" class="col-12 p-0">
                    <administrateur-dashboard></administrateur-dashboard>
                </div>
                <div  v-else-if="$user.role == 'gestionnaire'" class="col-12 p-0">
                    <gestionnaire-dashboard></gestionnaire-dashboard>
                </div>
                <div v-else class="col-12 p-0">
                    <utilisateur-dashboard></utilisateur-dashboard>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isLoading: false,
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
                errors: [],
                base_url: base_url,
                height: '100vh',
            }
        },
        computed: {
            computedHeight: function () {
                if(this.$user.authenticated == true){
                    this.height = '89vh';
                }
                return this.height;
            }
        },
        methods: {
            detectMobile: function(event){
                this.isMobile = event;
            },
        },
        mounted(){
            // console.log(this.config);
        }
    }
</script>

<style>
    /* Surcharge bootstrap + general*/
    .bg-primary{
        background-color: #09589D!important;
    }
    .btn-primary {
        color: #fff;
        background-color: #09589D!important;
        border-color: #09589D!important;
    }
    .btn-primary:hover {
        color: #fff;
        background-color: #063f70!important;
        border-color: #063f70!important;
    }
    .border-primary {
        border-color: #09589D!important;
    }
    .text-primary{
        color: #09589D!important;
    }

    .bg-success{
        background-color: #84B826!important;
    }
    .btn-success {
        color: #fff;
        background-color: #84B826!important;
        border-color: #84B826!important;
    }
    .btn-success:hover{
        color: #fff;
        background-color: #61871b!important;
        border-color: #61871b!important;
    }
    .border-success {
        border-color: #84B826!important;
    }
    .text-success{
        color: #84B826!important;
    }
    a {
        color: #09589D;
        text-decoration: none;
        background-color: transparent;
    }
    .enlargeable-image .full.enlarged{
        z-index: 9999!important;
    }

</style>