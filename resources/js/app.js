/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*
 * Thème Argon
 */
import SidebarPlugin from './components/utilitaires/SidebarPlugin';
Vue.use(SidebarPlugin);

import { FadeTransition } from 'vue2-transitions';
Vue.component('fade-transition', FadeTransition);

Vue.component('dashboard-navbar', require('./components/layout/DashboardNavbar.vue').default);
Vue.component('content-footer', require('./components/layout/ContentFooter.vue').default);
Vue.component('base-header', require('./components/utilitaires/BaseHeader.vue').default);
Vue.component('navbar-toggle-button', require('./components/utilitaires/Navbar/NavbarToggleButton').default);
Vue.component('base-dropdown', require('./components/utilitaires/BaseDropdown.vue').default);
Vue.component('base-nav', require('./components/utilitaires/Navbar/BaseNav').default);
Vue.component('notifications-component', require('./components/utilitaires/NotificationsComponent.vue').default);
Vue.component('stats-card', require('./components/utilitaires/Cards/StatsCard.vue').default);

import vClickOutside from 'v-click-outside'
Vue.use(vClickOutside)

/*
 * Tooltip / floating vue
 */
import FloatingVue from 'floating-vue';
import 'floating-vue/dist/style.css';
Vue.use(FloatingVue);


/*
 * Boostrap pour vue
 */
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
window.Vue.use(BootstrapVue)

/*
 * Import vuejs Notifications
 */
import Notifications from 'vue-notification';
window.Vue.use(Notifications);

/*
 * Vue Router pour le système de routing
 */
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/*
 * Import vue multi-select
 */
import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

/*
 * Vue js modal
 */
import VModal from 'vue-js-modal/dist/index.nocss.js';
import 'vue-js-modal/dist/styles.css';
window.Vue.use(VModal);

/*
 * Import vuejs autocomplete
 */
import AutocompleteComponent from 'vuejs-auto-complete';
Vue.component('autocomplete', AutocompleteComponent);


/*
 * Import vuejs datepicker
 */
import Datepicker from 'vuejs-datepicker';
Vue.component('vuejs-datepicker', Datepicker);
import {fr} from 'vuejs-datepicker/dist/locale';
Vue.prototype.$fr = fr;
Vue.prototype.$highlighted = {dates: [new Date()]};

/*
 * Layout
 */
Vue.component('pram-layout', require('./components/layout/pramLayout.vue').default);
Vue.component('entete-layout', require('./components/layout/enteteLayout.vue').default);

/*
 * Utilitaires
 */
Vue.component('is-mobile', require('./components/utilitaires/isMobileComponent.vue').default);
Vue.component('pagination', require('./components/utilitaires/paginationComponent.vue').default);
Vue.component('perPage', require('./components/utilitaires/perPageComponent.vue').default);
Vue.component('tableSortButton', require('./components/utilitaires/tableSortButton.vue').default);
Vue.component('searchBarComponent', require('./components/utilitaires/searchBarComponent.vue').default);
Vue.component('filtres-carte-component', require('./components/carte/FiltresCarteComponent.vue').default);
Vue.component('mini-carte-component', require('./components/carte/MiniCarteComponent.vue').default);
Vue.component('mare-show-component', require('./components/mares/MareShowComponent.vue').default);
Vue.component('l-pagination', require('./components/utilitaires/LaravelPaginationComponent.vue').default);
Vue.component('file-upload-component', require('./components/utilitaires/FileUploadComponent.vue').default);


/*
 * Permet la suppression d'une entrée dans tous les composants parametres index
 */
Vue.component('delete-parametre-data-component', require('./components/utilitaires/DeleteParametreDataComponent.vue').default);


/*
 * Import overlay loading component & stylesheet
 */
import LoadingComponent from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.component('loading', LoadingComponent);


/*
 * AOS pour les effet de scroll
 */
import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles
// ..
AOS.init();


/*
 * Agrandissement image
 */
Vue.component('enlargeable-image', require('@diracleo/vue-enlargeable-image').default);


/*
 * Dashboard
 */
Vue.component('developpeur-dashboard', require('./components/accueil/dashboards/DeveloppeurDashboardComponent.vue').default);
Vue.component('utilisateur-dashboard', require('./components/accueil/dashboards/UtilisateurDashboardComponent.vue').default);
Vue.component('administrateur-dashboard', require('./components/accueil/dashboards/AdministrateurDashboardComponent.vue').default);
Vue.component('gestionnaire-dashboard', require('./components/accueil/dashboards/GestionnaireDashboardComponent.vue').default);


/*
 * Widget
 */
Vue.component('widget-statistiques-mares', require('./components/accueil/widget/WidgetStatistiquesMaresComponent.vue').default);
Vue.component('widget-statistiques-mes-mares', require('./components/accueil/widget/WidgetStatistiquesMesMaresComponent.vue').default);
Vue.component('widget-statistiques-mes-fiches', require('./components/accueil/widget/WidgetStatistiquesMesFichesComponent.vue').default);
Vue.component('widget-admin-gestionnaire', require('./components/accueil/widget/WidgetAdminGestionnaireComponent.vue').default);
Vue.component('widget-bouton-accueil', require('./components/accueil/widget/WidgetBoutonAccueilComponent.vue').default);


/*
 * Composant vegetation mare
 */
Vue.component('vegetation-component', require('./components/mares/VegetationComponent.vue').default);


/*
 * Initialisation des routes, import du fichier routes.js
 */
import { routes } from './routes';

Vue.prototype.$user = JSON.parse(document.querySelector("#app").dataset.user);
let user = JSON.parse(document.querySelector("#app").dataset.user);

Vue.prototype.$config = JSON.parse(document.querySelector("#app").dataset.config);

const router = new VueRouter({ routes })

router.beforeEach((to, from, next) => {
	if(to.matched.some(record => record.meta.roles)){
		let autorisation = false;
		to.matched.forEach(function(match){
			if(match.meta.roles.indexOf(user.role) != -1) autorisation = true;
			else autorisation = false;
		});

		if(autorisation == true) next();
		else next('/errors/404');


	}else next();
})


const MyPlugin = {
	install(Vue, options){
		Vue.prototype.paramsForCustomEditorToolbar = () => {
            let toolbar = [
                // [{ font: [] }],

                [{ header: [false, 1, 2, 3, 4, 5, 6] }],

                // [{ size: ["small", false, "large", "huge"] }],

                ["bold", "italic", "underline", "strike"],

                [
                { align: "" },
                { align: "center" },
                { align: "right" },
                { align: "justify" }
                ],

                // [{ header: 1 }, { header: 2 }],

                ["blockquote", "code-block"],

                [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],

                // [{ script: "sub" }, { script: "super" }],

                [{ indent: "-1" }, { indent: "+1" }],

                [{ color: []}, {color: ['#2F2F2F']}, { background: [] }],

                // [{ color: [] }, { background: [] }],

                // ["link", "image", "video", "formula"],

                ["link"]

                // [{ direction: "rtl" }],
                // ["clean"]
            ];

            return toolbar;
        }
        Vue.prototype.capitalizeFirstLetter = (string) =>{
        	return string.charAt(0).toUpperCase() + string.slice(1);
    	}
    	Vue.prototype.cutLongString = (string, nb_character) =>{
    		if(string != null && string.length > nb_character){
    			return string.substr(0, nb_character);
    		}
    		return string;
    	}
		Vue.prototype.colorInputError = (refs, ref) =>{
			if(refs[ref] != undefined && refs[ref].classList != undefined){
				return refs[ref].classList.value += ' is-invalid';
			}
			else if(refs[ref].$el != undefined && refs[ref].$el.classList != undefined){
				return refs[ref].$el.classList.value += ' is-invalid';
			}
    	}
    	Vue.prototype.resetColorInputError = (refs) =>{
    		Object.keys(refs).forEach(el => {
            	if(refs[el] != undefined && refs[el].classList != undefined && refs[el].classList.contains('is-invalid')){
            		refs[el].classList.remove('is-invalid')
            	}
            	if(refs[el].$el != undefined && refs[el].$el.classList != undefined && refs[el].$el.classList.contains('is-invalid')){
            		refs[el].$el.classList.remove('is-invalid')
            	}
			});
    	}
    	Vue.prototype.inArray = (array, element, search) => {
    		let exist = array.map(function(x){
    			return x[element]
    		}).indexOf(search);
    		return exist;
    	}
    	Vue.prototype.ucFirst = (string) =>{
    		return string.charAt(0).toUpperCase() + string.slice(1);
    	}
		Vue.prototype.convertDate = (date) => {
			if(date != null && date != '' && date != undefined){
				return moment(date).format('DD/MM/YYYY');
			}
			return;
		}
		Vue.prototype.convertDateHeure = (date) => {
			return moment(date).format('DD/MM/YYYY HH:mm') + 'h';
		}
		Vue.prototype.convertHeure = (date) => {
			return moment(date).format('HH:mm') + 'h';
		}
		Vue.prototype.capitalizeFirstLetter = (string) => {
			if(string != null && string != undefined && string != ''){
				return string.charAt(0).toUpperCase() + string.slice(1);
			}
			return string;
		}

		/*
		 * Redirect si detection d'une erreur (exemple session plus active)
		 */
		Vue.prototype.checkError = (error) => {
			if (error.response) {
                console.log(error.response.data);
                console.log(error.response.status);
                console.log(error.response.headers);
                if(error.response.status == 401){
                	alert("Votre session n'est plus active");
                	return window.location = user.base_url;
                }
                if(error.response.status == 403){
                	console.clear();
                	return router.push({path: '/errors/404'});
                }
            }
		}

		Vue.prototype.objectSize = (obj) => {
			var size = 0,
	    	key;
	  		for (key in obj) {
	    		if (obj.hasOwnProperty(key)) size++;
	  		}
	  		return size;
		}
		/*
		 * Fonction si erreur, affiche avec vue notify
		 */
		Vue.prototype.showErrors = (errors) => {
			let string = '<ul>';
            errors.forEach(function(item){
                string += '<li>'+item+'</li>';
            })
            string += '</ul>';

            Vue.notify({
                group: 'notifications',
                title: 'Erreur',
                type: 'error',
                duration: 5000,
                text: string
            });
		}
		/*
		 * Fonction si success, affiche avec vue notify
		 */
		Vue.prototype.showSuccess = (success) => {
			let string = '<ul>';
            success.forEach(function(item){
                string += '<li>'+item+'</li>';
            })
            string += '</ul>';

            Vue.notify({
                group: 'notifications',
                title: 'Succès',
                type: 'success',
                duration: 5000,
                text: string
            });
		}
		Vue.prototype.parseErrorMessage = (message) => {
		    let errors = [];
		    if(message != null){
		        if((typeof message === 'object' && !Array.isArray(message)) || Array.isArray(message)){
		            Object.keys(message).map(function(objectKey, index) {
		                let value = message[objectKey];
		                errors.push(value[0]);
		            });
		        }else{
		            errors.push(message);
		        }
		    }

		    return errors;
		}
		Vue.prototype.authorisations = (auto) => {
			let autorisations_user = [];
			if(user.role === 'developpeur' || user.role === 'administrateur'){
				autorisations_user = [
					'access_config',
					'access_parametres',
					'access_validation_mares_fiches',
					'access_validation_compte',
					'access_utilisateurs',
					'access_groupes',
					'access_modification_formulaire'
				];
			}else if(user.role === 'gestionnaire'){
				autorisations_user = [
					'access_parametres',
					// 'access_config',
					'access_validation_mares_fiches',
				];
			}else if(user.role === 'utilisateur'){
				autorisations_user = [];
			}

			if(autorisations_user.includes(auto)){
				return true;
			}

			return false
		}
		/*
		 * Ouvre le calendrier si click avec vuejs datepicker
		 */
		Vue.prototype.datePickerOpened = (reference, $refs) => {
			$refs[reference].showCalendar();
            $refs[reference].$el.querySelector('input').focus();
		}

		// Vue.config.warnHandler = function(msg, vm, trace) {
		//     const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
		//     var req = new XMLHttpRequest();
		//     let line = null;
		//     let url = null;
		//     let route = vm.$route && vm.$route.fullPath ? vm.$route.fullPath : null;
		//     let params = "msg=Warn:" + msg + "&trace=" + trace.replace(/(\r\n|\n|\r)/gm, "") + "&route=" + route;
		//     req.open("POST", base_url + "/developpeur/write_javacript_log", true);
		//     req.setRequestHeader('X-CSRF-Token', token);
		//     req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
		//     req.send(params);
		//     return;
		// }
	}
}

Vue.use(MyPlugin)

const app = new Vue({router}).$mount('#app')
