<template>
    <div class="wrapper">
    	<is-mobile ref="isMobileComponent" v-on:detectMobile="detectMobile"></is-mobile>
        <side-bar :style="$user.authenticated == true ? 'display:block' : 'display:none'">
            <template slot="links">
            	<sidebar-item
                    :link="{
                        name: 'Accueil',
                        path: '/',
                        icon: 'fas fa-home text-primary',
                    }"
                >
                </sidebar-item>

                <sidebar-item
                    :link="{
                        name: 'Carte',
                        path: '/carte',
                        icon: 'fas fa-map-marked-alt text-orange',
                    }"
                >
                </sidebar-item>

            	<sidebar-item
                    :link="{
                        name: 'Mes mares',
                        icon: 'fas fa-water text-success'
                    }"
                >
                    <sidebar-item
                        :link="{ name: 'Toutes mes mares', path: '/mes_mares' }"
                    ></sidebar-item>
                    <sidebar-item
                        :link="{ name: 'Toutes mes fiches', path: '/fiches_attentes' }"
                    ></sidebar-item>
                </sidebar-item>

                <sidebar-item v-if="authorisations('access_validation_mares_fiches')"
                    :link="{
                        name: 'Validation',
                        icon: 'fas fa-clipboard-check text-dark'
                    }"
                >
                    <sidebar-item
                        :link="{ name: 'Mares', path: '/validation_mares' }"
                    ></sidebar-item>
                    <sidebar-item
                        :link="{ name: 'Fiches', path: '/validation_fiches' }"
                    ></sidebar-item>
                    <sidebar-item v-if="authorisations('access_utilisateurs')"
                        :link="{ name: 'Comptes', path: '/validation_comptes' }"
                    ></sidebar-item>
                </sidebar-item>

                <sidebar-item
                    :link="{
                        name: 'Recherche',
                        path: '/recherche',
                        icon: 'fas fa-search text-info',
                    }"
                >
                </sidebar-item>

                <sidebar-item v-if="$user.role === 'administrateur' || $user.role === 'gestionnaire' || $user.role === 'developpeur'"
                    :link="{
                        name: 'Exports',
                        path: '/exports',
                        icon: 'fas fa-file-excel text-success',
                    }"
                >
                </sidebar-item>

                <sidebar-item v-if="authorisations('access_parametres')"
                    :link="{
                        name: 'Paramètres',
                        icon: 'fas fa-cogs text-yellow'
                    }"
                >
                    <sidebar-item v-if="authorisations('access_utilisateurs')"
                        :link="{ name: 'Utilisateurs', path: '/utilisateurs' }"
                    ></sidebar-item>
                    <sidebar-item v-if="authorisations('access_groupes')"
                        :link="{ name: 'Groupes', path: '/groupes' }"
                    ></sidebar-item>
                    <sidebar-item
                        :link="{ name: 'Autres', path: '/parametres' }"
                    ></sidebar-item>
                </sidebar-item>

                <sidebar-item
                    :link="{
                        name: 'Assistance',
                        icon: 'fas fa-question-circle text-info'
                    }"
                >
                    <sidebar-item
                        :link="{ name: 'Aides', path: '/aides' }"
                    ></sidebar-item>
                    <sidebar-item
                        :link="{ name: 'Améliorations', path: '/ameliorations' }"
                    ></sidebar-item>
                    <sidebar-item
                        :link="{ name: 'Tickets', path: '/tickets' }"
                    ></sidebar-item>
                </sidebar-item>


                <a :href="$user.logout_path" class="nav-link nav-item" onclick="deconnexionButton()">
                    <i class="fas fa-door-open text-danger"></i>
                    <span class="nav-link-text">Deconnexion</span>
                </a>
            </template>
        </side-bar>

        <div class="main-content" :style="$user.authenticated == true ? 'margin-left:62px' : 'margin-left:0'">
			<dashboard-navbar
			v-if="$user.authenticated == true"
			:type="$route.meta.navbarType"
			v-bind:notifs="notifications"
			v-bind:clear_cache="clear_cache"
			v-bind:users="users"
			v-bind:change_user="change_user"
			v-bind:admin_id="admin_id"
			v-on:reloadAgenda="reloadAgenda"
			></dashboard-navbar>
	        <notifications group="notifications" position="bottom left" width="500"></notifications>

	        <div @click="$sidebar.displaySidebar(false)">
	            <fade-transition name="component-fade" mode="out-in">
			        <router-view name="default"></router-view>
			    </fade-transition>
	        </div>
	        <content-footer v-if="!$route.meta.hideFooter && $user.authenticated == true" v-bind:versionApp="version_app"></content-footer>
	    </div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				isMobile: false,
				base_url: base_url,
				notifications: false,
				reload_agenda: false,
				clear_cache: '',
				users: [],
				change_user: '',
				admin_id: '',
				import_communes: '',
				import_sites: '',
				import_commune_site: '',
				import_intitules_postes: '',
				version_app: '',
				config: [],
			}
		},
		watch: {
			reload_agenda: function(val){
				this.reload_agenda = val;
			}
		},
		methods: {
			checkIfNotifFromAgenda: function(){
				this.checkNotificationWithoutTimer();
			},
			detectMobile: function(event){
            	this.isMobile = event;
        	},
        	reloadAgenda: function(event){
        		this.reload_agenda = event;
        	},
        	getUrl: function(){
        		const self = this;
				axios.get(base_url + '/get_url')
				.then(function (resp) {
					self.clear_cache = resp.data.clear_cache;
					self.users = resp.data.users;
					self.change_user = resp.data.change_user;
					self.admin_id = resp.data.admin_id;
					self.import_communes = resp.data.import_communes;
					self.import_sites = resp.data.import_sites;
					self.import_commune_site = resp.data.import_commune_site;
					self.import_intitules_postes = resp.data.import_intitules_postes;
					self.version_app = resp.data.version_app;
				})
				.catch(function (resp) {
					return self.checkError(resp);
				});
        	},
        	checkNotification() {
        		let self = this;
	        	let sliderTimer = setInterval(function(){
	          		axios.get(base_url + '/check_if_notifications')
		            .then(function (resp) {
		                let agenda_notifications = resp.data.agenda_invitations;
		                if(Number(agenda_notifications) > 0){
		                	self.notifications = true;
		                }else{
		                	self.notifications = false;
		                }
		            })
		            .catch(function (resp) {
		                return self.checkError(resp);
		            });
	        	}, 60000);
	        // }, 10000);
	    	},
	    	checkNotificationWithoutTimer() {
        		let self = this;
          		axios.get(base_url + '/check_if_notifications')
	            .then(function (resp) {
	                let agenda_notifications = resp.data.agenda_invitations;
	                if(Number(agenda_notifications) > 0){
	                	self.notifications = true;
	                }else{
	                	self.notifications = false;
	                }
	            })
	            .catch(function (resp) {
	                return self.checkError(resp);
	            });
	    	},
		},
		mounted(){
			// this.checkNotificationWithoutTimer();
			// this.checkNotification();
            this.getUrl();
		}
	}


</script>