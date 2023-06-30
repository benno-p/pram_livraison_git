<template>
	<base-nav
	container-classes="container-fluid"
	class="navbar-top navbar-expand"
	:class="{'navbar-dark': type === 'default'}"
	>
		<!-- <a href="#" aria-current="page" class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block active router-link-active"> {{$route.name}} </a> -->
		<b-navbar-nav class="align-items-center ml-md-auto">
			<li class="nav-item d-sm-none">
				<a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
					<i class="ni ni-zoom-split-in"></i>
				</a>
			</li>
		</b-navbar-nav>

		<b-navbar-nav class="align-items-center ml-auto ml-md-0">
			<div class="form-group mr-4 mb-0">
				<!-- <form id="change_users" :action="change_user" method="POST" class="form-inline clearfix" v-if="$user.role == 'developpeur'"> -->
				<form method="POST" class="form-inline clearfix" v-if="$user.role == 'developpeur'">
					<div class="form-group">
						<select class="form-control mx-3" @change="changeUserFormSubmit($event.target.value)">
							<option value="">-- Se connecter en tant que --</option>
							<option v-for="u in users" :value="u.id">
								{{u.nom ? u.nom : ''}} {{u.prenom ? u.prenom : ''}} - {{u.role_id ? u.role.nom : 'N/C'}}
							</option>
						</select>
					</div>
				</form>
			</div>

			<base-dropdown
			menu-on-right
			class="nav-item"
			tag="li"
			title-tag="a"
			title-classes="nav-link pr-0">
				<a href="#" class="nav-link pr-0" @click.prevent slot="title-container">
					<b-media no-body class="align-items-center">
						<b-media-body class="ml-2 d-none d-lg-block">
							<span class="mb-0 text-sm  font-weight-bold">{{$user.prenom}} {{$user.nom}}</span>
						</b-media-body>
					</b-media>
				</a>

				<template>
					<b-dropdown-header class="noti-title">
						<h6 class="text-overflow m-0">Bienvenue!</h6>
					</b-dropdown-header>
					<b-dropdown-item href="#!">
						<router-link to="/password" class="dropdown-item">
							<i class="fas fa-unlock-alt"></i>Modifier mon mot de passe
						</router-link>
					</b-dropdown-item>
					
					<template v-if="$user.role === 'developpeur'">
						<b-dropdown-item href="#!">
							<a class="dropdown-item pointer" :href="clear_cache">
								<i class="fas fa-eraser"></i> Vider le cache
							</a>
						</b-dropdown-item>
					</template>

					<b-dropdown-item v-if="admin_id != ''">
						<button class="dropdown-item pointer" @click="changeUserFormSubmit(admin_id)">
							<i class="fas fa-jedi"></i> Revenir du bon côté
						</button>
					</b-dropdown-item>
					
					<div class="dropdown-divider"></div>
					
					<b-dropdown-item href="#!">
						<a class="dropdown-item" :href="$user.logout_path" onclick="deconnexionButton()">
							<i class="fas fa-door-open"></i> Déconnexion
						</a>
					</b-dropdown-item>
				</template>
			</base-dropdown>
		</b-navbar-nav>
	</base-nav>
</template>

<script>
import { CollapseTransition } from 'vue2-transitions';
// import { BaseNav, Modal } from '@/components';

export default {
	components: {
		CollapseTransition,
		// BaseNav,
		// Modal
	},
	props: {
		type: {
			type: String,
			default: 'default', // default|light
			description: 'Look of the dashboard navbar. Default (Green) or light (gray)'
		},
		notifs: Boolean,
		clear_cache: String,
		users: Array,
		change_user: String,
		admin_id: [String, Number],
		avatarProps: String,
	},
	computed: {
		routeName() {
			const { name } = this.$route;
			return this.capitalizeFirstLetter(name);
		}
	},
	data() {
		return {
			activeNotifications: false,
			showMenu: false,
			searchModalVisible: false,
			searchQuery: '',
			isLoading: true,
			notifications: this.notifs,
			avatar: '',
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
		reloadAgenda: function(event){
    		this.$emit('reloadAgenda', event);
    	},
		changeUserFormSubmit(user_id) {
			let self = this;
            let formData = new FormData();
            self.errors = [];

            formData.append('id', user_id);

            axios.post(self.change_user, formData, { 
                _method: 'POST',
            })
            .then(function (response) {
                if(response.data.error == true){
                    Object.keys(response.data.message).map(function(objectKey, index) {
                        let value = response.data.message[objectKey];
                        self.errors.push(value[0]);
                    });
                    self.showErrors(self.errors);
                    return false;
                }else{
                    return window.location.href = response.data.url;
                }
            })
            .catch(function (error) { 
                return self.checkError(error); 
            });

		},
		capitalizeFirstLetter(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		toggleNotificationDropDown() {
			this.activeNotifications = !this.activeNotifications;
		},
		closeDropDown() {
			this.activeNotifications = false;
		},
	},
	mounted(){
		// this.avatar = this.$user.avatar;
		// this.avatar = this.getBase64Image();
		this.avatar = this.$user.avatar;
		// console.log(this.avatar);
		// this.toDataURL(base_url+'/images/phonebook.jpg', function(dataUrl) {
		// 	console.log('RESULT:', dataUrl)
		// })
	}
};
</script>


<!-- <script>
function toDataURL(url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    var reader = new FileReader();
    reader.onloadend = function() {
      callback(reader.result);
    }
    reader.readAsDataURL(xhr.response);
  };
  xhr.open('GET', url);
  xhr.responseType = 'blob';
  xhr.send();
}

toDataURL('https://cdn.discordapp.com/attachments/571092147801948204/784586541146177606/6f32c864-985a-481d-8d8e-bd1f14ab9951.png', function(dataUrl) {
  console.log('RESULT:', dataUrl)
})


</script> -->