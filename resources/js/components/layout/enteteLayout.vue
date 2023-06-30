<template>
    <div>    
        <base-header class="pb-6 pb-8 pt-8 pt-md-7 bg-gradient-success" v-if="$user.authenticated == true">
            <b-row>
                <b-col xl="12" md="12">
                    <stats-card type="gradient-red" :sub-title="$route.meta.title" class="mb-4">
                    </stats-card>
                </b-col>
            </b-row>
        </base-header>

        <is-mobile ref="isMobileComponent" v-on:detectMobile="detectMobile"></is-mobile>
        <div class="vld-parent">
            <loading :active.sync="isLoading" :is-full-page="fullPage" :color="'#3A55AD'"></loading>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['isLoading'],
        data: function(){
            return {
                fullPage: false,
                windowWidth: window.innerWidth,
                isMobile: false,
            }
        },
        methods: {
            detectMobile: function(event){
                this.isMobile = event;
            },
        },
        mounted(){
    
        }
    }
</script>