<template>
    <div>
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white menu-responsive pt-3">
        <div class="container-fluid">

            <!--Toggler-->
            <navbar-toggle-button @click.native="showSidebar">

            </navbar-toggle-button>
            
            <router-link class="navbar-brand" to="/">
                <img :src="logo" class="navbar-brand-img" alt="...">
            </router-link>

            <slot name="mobile-right">
                <ul class="nav align-items-center d-md-none">
<!--                     <div class="">
                        <notifications-component 
                            v-bind:notifications="notifications"
                            v-on:reloadAgenda="reloadAgenda"
                            v-on:changeNotifications="changeNotifications"
                        ></notifications-component>
                    </div> -->

                    <base-dropdown class="nav-item" menu-on-right tag="li" title-tag="a">
                        <a slot="title-container" class="nav-link" href="#" role="button">
                            <div class="media align-items-center">
                                {{$user.prenom}}
<!--                                 <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" :src="avatar">
                                </span> -->
                            </div>
                        </a>

                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Bienvenue!</h6>
                        </div>
                        
                        <router-link to="/password" class="dropdown-item">
                            <i class="fas fa-unlock-alt"></i>Modifier mon mot de passe
                        </router-link>
                        <router-link to="/profil" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>Modifier mon profil
                        </router-link>
                        
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" :href="$user.logout_path" onclick="deconnexionButton()">
                            <i class="fas fa-door-open"></i> Déconnexion
                        </a>
                    </base-dropdown>
                </ul>
            </slot>
            
            <slot></slot>
            
            <div v-show="$sidebar.showSidebar" class="navbar-collapse collapse show" id="sidenav-collapse-main">
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <router-link to="/">
                                <img :src="logo">
                            </router-link>
                        </div>
                    <div class="col-6 collapse-close">
                        <navbar-toggle-button @click.native="closeSidebar"></navbar-toggle-button>
                    </div>
                </div>
            </div>

            <ul class="navbar-nav">
                <slot name="links">
                </slot>
            </ul>
            </div>
        </div>
    </nav>

    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white ps ps--active-y menu-desktop" id="sidenav-main">
        <div class="scrollbar-inner">
            <div class="sidenav-header d-flex align-items-center mb-4">
                <router-link class="navbar-brand" to="/">
                    <img :src="logo" class="navbar-brand-img" :alt="logo_alt">
                </router-link>
            </div>
                        
            <div class="navbar-inner">
                <slot></slot>
                <ul class="navbar-nav">
                    <slot name="links">
                    </slot>
                </ul>
            </div>
        </div>
    </nav>
    </div>
</template>
<script>
// import NavbarToggleButton from '../NavBar/NavbarToggleButton'

export default {
    name: 'sidebar',
    components: {
        // NavbarToggleButton
    },
    props: {
        // logo: {
        //     type: String,
        //     // default: 'img/brand/green.png',
        //     default: 'images/logo/logo.png',
        //     description: 'Sidebar app logo'
        // },
        autoClose: {
            type: Boolean,
            default: true,
            description: 'Whether sidebar should autoclose on mobile when clicking an item'
        },
        notifs: Boolean,
        avatarProps: String,
        // logo: String,
    },
    data(){
        return{
            notifications: this.notifs,
            avatar: '',
            logo : 'images/logo/logo.png',
            logo_alt: 'Logo',
        }
    },
    provide() {
        return {
            autoClose: this.autoClose
        };
    },
    watch:{
        notifs: function(val){
            this.notifications = val;
        },
        avatarProps: function(val){
            this.avatar = val;
        }
    },
    methods: {
        changeNotifications: function(val){
            // console.log(val)
            this.notifications = val;
            this.$emit('changeNotificationsFromNavbar', val);
        },
        closeSidebar() {
            this.$sidebar.displaySidebar(false)
        },
        showSidebar() {
            this.$sidebar.displaySidebar(true)
        },
        reloadAgenda: function(event){
            this.$emit('reloadAgenda', event);
        },
    },
    beforeDestroy() {
        if (this.$sidebar.showSidebar) {
            this.$sidebar.showSidebar = false;
        }
    },
    mounted(){
        this.avatar = this.$user.avatar;
        // console.log(this.$config.logo);
        this.logo = this.$config && this.$config.logo ? base_url + '/configuration/logo/'+this.$config.logo : 'images/logo/logo.png';
    }
};
</script>
