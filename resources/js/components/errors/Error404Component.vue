<template>
	<div>
		<!-- Header -->
		<div class="header bg-gradient-info py-7 py-lg-8 pt-lg-9">
			<b-container>
				<div class="header-body text-center mb-7">
					<b-row class="justify-content-center">
						<b-col xl="5" lg="6" md="6" class="px-5">
							<h1 class="text-light">Ooops!</h1>
							<p class="text-lead text-white">Page non trouvée. Ne vous inquiétez pas, nous avons plein d'autres pages à explorer</p>

							<router-link to="/" class="btn btn-success btn-lg m-4">
		                        <i class="fas fa-home"></i> Retour à l'accueil
		                    </router-link>
						</b-col>
					</b-row>
				</div>
			</b-container>
			<div class="separator separator-bottom separator-skew zindex-100">
				<svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
					<polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
				</svg>
			</div>
		</div>
		<!-- Page content -->
		<section class="py-6" style="background-color: #172b4d!important">
			<b-container >
				<b-row align-v="center" class="row-grid">
					<b-col md="6">
						<b-img src="images/logo/logo.png" fluid />
					</b-col>
					<b-col md="6">
						<div class="pr-md-5">
							<h1 class="text-light">Plan du site</h1>
							<p class="text-light">Vous pouvez vous rendre ici :</p>
							<ul style="list-style: none;">
								<li>
									<router-link to="/" class="font-weight-bold text-success mt-5">Tableau de bord</router-link>
								</li>
								<li>
									<router-link to="/carte" class="font-weight-bold text-success mt-5">Carte</router-link>
								</li>
								<li>
									<router-link to="/mes_mares" class="font-weight-bold text-success mt-5">Toutes mes mares</router-link>
								</li>
							</ul>
						</div>
					</b-col>
				</b-row>
			</b-container>
		</section>
	</div>
</template>

<script>

export default {
	name: 'not-found'
};
</script>
