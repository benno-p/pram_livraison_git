<template>
	<div class="position-relative" id="bloc_aide">
		<entete-layout :isLoading="isLoading"></entete-layout>

        <b-container fluid class="mt--9" style="min-height: 60vh!important;">
			<h4>Aides</h4>
			<p>
				Vous trouverez ici des informations concernant l'utilisation de l'application cartographique du Programme régional d'actions en faveur des mares du Grand Est
			</p>
			<div class="card" v-if="$user.role === 'developpeur' || $user.role === 'administrateur'">
				<div class="card-header">
					<h5><i class="fas fa-user"></i> Rôles</h5>
				</div>
				<div class="card-body">
					<p>Voici la liste des actions possibles en fonction des différents rôles :</p>
					<ul>
						<li>Visiteur
							<ul>
								<li>Personne n'ayant pas de compte sur l'application. Peut uniquement consulter les mares.</li>
							</ul>
						</li>
						<li>Utilisateur
							<ul>
								<li>Peut consulter, saisir une nouvelle mare et saisir une nouvelle fiche pour une mare existante.</li>
								<li>La saisie d'une nouvelle mare ou d'une nouvelle fiche doit être validée par un gestionnaire.</li>
							</ul>
						</li>
						<li>Gestionnaire
							<ul>
								<li>Même chose que pour un utilisateur</li>
								<li>Un gestionnaire est associé à un ou plusieurs départements, c'est lui qui pourra valider les nouvelles mares et nouvelles fiches si elles ont été saisies dans son/ses département(s) respectif(s).</li>
							</ul>
						</li>
						<li>Administrateur
							<ul>
								<li>Même chose que pour un gestionnaire</li>
								<li>L'administrateur peut créer des utilisateurs. C'est aussi lui qui valide un compte utilisateur lorsqu'une demande est soumise.</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>

			<div class="card mt-4">
				<div class="card-header">
					<h5><i class="fas fa-file-alt"></i> Saisir et modifier des mares</h5>
				</div>
				<div class="card-body">
					<div class="card mb-4">
						<div class="card-header">
							<h5 class="text-cen-orange">Table des matières</h5>
						</div>
						<div class="card-body">
							<ul>
								<li>
									<a href="#:javascript" @click="scrollMeTo('plan_saisir_nouvelle_mare')">Saisir une nouvelle mare</a>
								</li>
								<li>
									<a href="#:javascript" @click="scrollMeTo('consulter_mare_existante')">Consulter une mare existante</a>
								</li>
								<li>
									<a href="#:javascript" @click="scrollMeTo('nouvelle_fiche_mare')">Nouvelle fiche pour une mare</a>
								</li>
								<li>
									<a href="#:javascript" @click="scrollMeTo('validation_mare_fiche')">Validation d'une mare ou d'une fiche</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="card mb-4" ref="plan_saisir_nouvelle_mare">
						<div class="card-header">
							<h5 class="text-cen-orange">Saisir une nouvelle mare</h5>
						</div>
						<div class="card-body">
							<ul>
								<li>Pour saisir une nouvelle mare, il suffit de cliquer à l'endroit souhaité sur la carte en ayant au préalable suffisamment zoomé.<br>
								Un pop-up va apparaitre et vous demander si vous souhaitez créer une nouvelle mare (cf image suivante).</li>
								<enlargeable-image :src="base_url + '/images/aides/popup.PNG'" :src_large="base_url + '/images/aides/popup.PNG'" class="img-fluid mb-4 mt-4 col-12 col-md-4 border p-0" />
								<li>Lorsque vous cliquerez sur "OK", le formulaire de création d'une mare apparaîtra.</li>
								<!-- <li>Veillez à bien remplir les champs, vous ne pourrez plus modifier la fiche une fois qu'elle aura été soumise.</li> -->
								<li>Les champs obligatoires comportent un astérisque <span class="text-danger font-weight-bold">*</span>.</li>
								<li>Une fois le formulaire envoyé, il sera soumis à validation. (Voir <a href="#:javascript" @click="scrollMeTo('validation_mare_fiche')">Validation d'une mare ou d'une fiche</a>)</li>
							</ul>
						</div>
					</div>


					<div class="card mb-4" ref="consulter_mare_existante">
						<div class="card-header">
							<h5 class="text-cen-orange">Consulter une mare existante</h5>
						</div>
						<div class="card-body">
							<ul>
								<li>Pour consulter une mare existante, il suffit de cliquer sur un point après avoir suffisamment zoomé. Cliquez ensuite sur le bouton "consulter" (cf image suivante).</li>
								<enlargeable-image :src="base_url + '/images/aides/consulter.PNG'" :src_large="base_url + '/images/aides/consulter.PNG'" class="img-fluid mb-4 mt-4 col-12 col-md-4 border p-0" />
								<li>Vous verrez ensuite les données générales de la mare et toutes les fiches associées à celle-ci.</li>
								<li>Vous pouvez naviguer entre les fiches en cliquant dessus (cf image suivante).</li>
								<enlargeable-image :src="base_url + '/images/aides/naviguer_fiche.PNG'" :src_large="base_url + '/images/aides/naviguer_fiche.PNG'" class="img-fluid mb-4 mt-4 col-12 col-md-4 border p-0" />
							</ul>
						</div>
					</div>

					<div class="card mb-4" ref="nouvelle_fiche_mare">
						<div class="card-header">
							<h5 class="text-cen-orange">Créer une nouvelle fiche pour une mare existante</h5>
						</div>
						<div class="card-body">
							<ul>
								<li>Toujours dans l'onglet consulter une mare, pour créer une nouvelle fiche pour une mare existante, il suffit de cliquer sur le bouton vert "créer une nouvelle fiche"  (cf image suivante).</li>							
								<enlargeable-image :src="base_url + '/images/aides/nouvelle_fiche.PNG'" :src_large="base_url + '/images/aides/nouvelle_fiche.PNG'" class="img-fluid mb-4 mt-4 col-12 col-md-4 border p-0" />
								<li>Les champs obligatoires comportent un astérisque <span class="text-danger font-weight-bold">*</span>.</li>
								<li>Une fois le formulaire envoyé, il sera soumis à validation. (Voir <a href="#:javascript" @click="scrollMeTo('validation_mare_fiche')">Validation d'une mare ou d'une fiche</a>)</li>
							</ul>
						</div>
					</div>

					<div class="card mb-4" ref="validation_mare_fiche">
						<div class="card-header">
							<h5 class="text-cen-orange">Validation d'une mare ou d'une fiche</h5>
						</div>
						<div class="card-body">
							<ul>
								<li>Lorsque vous créez une mare ou une fiche, celle-ci sera soumise à validation avant d'être visible sur la carte.</li>
								<li>Pour consulter les mares non validées :
									<ul>
										<li>
			                                <router-link to="/mes_mares/attentes">
			                                    <i class="fas fa-angle-right"></i> Mares en attente
			                                </router-link>
			                            </li>
			                        </ul>
			                    </li>
			                    <li>Pour consulter les fiches non validées :
			                    	<ul>
				                    	<li>
				                            <router-link to="/fiches_attentes">
				                                <i class="fas fa-angle-right"></i> Fiches en attente
				                            </router-link>
				                        </li>
									</ul>
								</li>
								<li>Vous pouvez modifier une mare ou une fiche tant que celle-ci n'est pas validée.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</b-container>
	</div>
</template>


<script>
export default{
	data(){
		return{
			base_url: base_url,
			isLoading: false,
		}
	},
	methods:{
		scrollMeTo(refName) {
		    var element = this.$refs[refName];
		    var top = element.offsetTop + 600;

		    window.scrollTo({
		    	top: top, 
		    	left: 0,
		    	behavior: 'smooth'
		    });

		//     document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		//     anchor.addEventListener('click', function (e) {
		//         e.preventDefault();

		//         document.querySelector(this.getAttribute('href')).scrollIntoView({
		//             behavior: 'smooth'
		//         });
		//     });
		// });


		}
	}
}


</script>