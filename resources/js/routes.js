export const routes = [
	/*
	 * Routes accueil => texte accueil - demande de compte ou connexion
	 */
	{
		path: '/', 
		component: require('./components/accueil/AccueilComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Accueil'},
		children:[
		{
			path: '/',
			components: {second: require('./components/accueil/TexteAccueilComponent.vue').default},
			name:'Accueil',
			meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Accueil'}
		},
		{
			path: '/connexion',
			components: {second: require('./components/connexion/ConnexionComponent.vue').default},
			name:'connexion',
			meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Connexion'}
		},
		{
			path: '/demande_compte',
			components: {second: require('./components/demande_compte/DemandeCompteCreateComponent.vue').default},
			name:'demande_compte',
			meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Demande de création d\'un compte'}
		},
		]
	},



	/*
	 * Routes carte => consultation mares, fiches et creation fiches
	 */
	{
		path: '/carte',
		component: require('./components/carte/CarteComponent.vue').default,
		name:'carte',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Carte'},
		children: [
			{
				path: '/carte/create',
				components: {second: require('./components/mares/MareCreateComponent.vue').default},
				props: { mareInfo: false },
				name: 'carte.create',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Création d\'une mare'},
				children: [
					{
						path: '/carte/create/observateur',
						components: {modal: require('./components/mares/CreateObservateurComponent.vue').default},
						name: 'mare.create_observateur',
						meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Créer un observateur'},
					},
				]
			},
			{
				path: '/carte/consultation/:id',
				components: {second: require('./components/mares/MareConsultationComponent.vue').default},
				name: 'carte.consultation',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Consultation d\'une mare'},
				children: [
					{
						path: '/carte/consultation/:id',
						components: {third: require('./components/mares/MareFichesComponent.vue').default},
						name: 'mare.consultation',
						meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Consultation d\'une mare'},
					},
					{
						path: '/carte/consultation/:id/nouvelle_fiche',
						components: {third: require('./components/mares/MareFicheComponent.vue').default},
						name: 'mare.nouvelle_fiche',
						meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Nouvelle fiche'}
					},
				]
			},
		]
	},


	/*
	 * Consultation mes mares + edition des mares
	 */
	{
		path: '/mes_mares',
		component: require('./components/mes_mares/MareIndexComponent.vue').default,
		name:'mes_mares',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Mes mares'},
		children:[
			{
				path: '/mes_mares/commentaires',
				components: {second: require('./components/mares/CommentairesComponent.vue').default},
				name: 'mes_mares.commentaires',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Commentaires'}
			}
		]
	},
	{
		path: '/mes_mares/consultation/:id',
		component: require('./components/mares/MareConsultationComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'},
		children: [
			{
				path: '/mes_mares/consultation/:id',
				components: {third: require('./components/mares/MareFichesComponent.vue').default},
				name: 'mes_mares.consultation',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Consultation'},
			},
			{
				path: '/mes_mares/consultation/:id/nouvelle_fiche',
				components: {third: require('./components/mares/MareFicheComponent.vue').default},
				name: 'mes_mares.nouvelle_fiche',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'}
			},
		]
	},
	{
		path: '/mes_mares/edit/:id',
		component: require('./components/mares/MareConsultationComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Éditer une mare'},
		children: [
			{
				path: '/mes_mares/edit/:id',
				components: {third: require('./components/mares/MareFicheComponent.vue').default},
				name: 'mes_mares.edit',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur','administrateur'], title: 'Éditer une mare'},
				children: [
					{
						path: '/mes_mares/edit/:id/observateur',
						components: {modal: require('./components/mares/CreateObservateurComponent.vue').default},
						name: 'mes_mare.edit.create_observateur',
						meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Créer un observateur'},
					},
				]
			}
		]
	},






	/*
	 * Consultation fiche en attentes + edition fiche en attente
	 */
	{
		path: '/mes_fiches/consultation/:id',
		component: require('./components/mares/MareConsultationComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'},
		children: [
			{
				path: '/mes_fiches/consultation/:id',
				components: {third: require('./components/mares/MareFichesComponent.vue').default},
				name: 'mes_fiches.consultation',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Consultation'},
			},
			{
				path: '/mes_fiches/consultation/:id/nouvelle_fiche',
				components: {third: require('./components/mares/MareFicheComponent.vue').default},
				name: 'mes_fiches.nouvelle_fiche',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'}
			},
		]
	},
	{
		path: '/fiches_attentes',
		component: require('./components/mes_mares/FicheEnAttenteComponent.vue').default,
		name:'fiches_attentes',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Toutes mes fiches'},
		children:[
			{
				path: '/fiches_attentes/commentaires',
				components: {second: require('./components/mares/CommentairesComponent.vue').default},
				name: 'fiches_attentes.commentaires',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Commentaires'}
			}
		]
	},
	{
		path: '/fiches_attentes/edit/:id',
		component: require('./components/mares/MareConsultationComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Éditer une fiche'},
		children: [
			{
				path: '/fiches_attentes/edit/:id',
				components: {third: require('./components/mares/MareFicheComponent.vue').default},
				name: 'mes_mares.fiche_edit',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur','administrateur'], title: 'Éditer une fiche'},

			}
		]
	},


	/*
	 * Validation des comptes
	 */
	{
		path: '/validation_comptes',
		component: require('./components/demande_compte/DemandeCompteIndexComponent.vue').default,
		name:'validation_comptes',
		meta: {roles:['developpeur', 'administrateur'], title: 'Validation de comptes'}
	},
	{
		path: '/validation_comptes/edit/:id',
		component: require('./components/demande_compte/DemandeCompteEditComponent.vue').default,
		name:'validation_comptes.edit',
		meta: {roles:['developpeur', 'administrateur'], title: 'Validation de compte'}
	},


	/*
	 * Validation des mares
	 */
	{
		path: '/validation_mares',
		component: require('./components/validations/mares/ValidationMaresIndexComponent.vue').default,
		name:'validation_mares',
		meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Validation mares'},
		children:[
			{
				path: '/validation_mares/commentaires',
				components: {second: require('./components/validations/CommentairesComponent.vue').default},
				name: 'validation_mares.commentaires',
				meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Commentaires'}
			}
		]
	},
	{
		path: '/validation_mares/edit/:id',
		component: require('./components/validations/mares/ValidationMareComponent.vue').default,
		name:'validation_mares.edit',
		meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Validation mare'},
		children: [
			{
				path: '/validation_mares/edit/:id',
				components: {four: require('./components/mares/MareConsultationComponent.vue').default},
				name: 'validation_mares.consultation',
				meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Validation mare'},
				children: [
					{
						path: '/validation_mares/edit/:id',
						components: {third: require('./components/mares/MareFichesComponent.vue').default},
						name: 'validation_mares.fiches',
						meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Validation mare'},
					}
				]
			},
		]
	},


	/* 
	 * Validation des fiches
	 */
	{
		path: '/validation_fiches',
		component: require('./components/validations/fiches/ValidationFichesIndexComponent.vue').default,
		name:'validation_fiches',
		meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Validation fiches'},
		children:[
			{
				path: '/validation_fiches/commentaires',
				components: {second: require('./components/validations/CommentairesComponent.vue').default},
				name: 'validation_fiches.commentaires',
				meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Commentaires'}
			}
		]
	},
	{
		path: '/validation_fiches/edit/:id',
		component: require('./components/validations/fiches/ValidationFicheComponent.vue').default,
		name:'validation_fiches.edit',
		meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Validation fiche'},
		children: [
			{
				path: '/validation_fiches/edit/:id',
				components: {four: require('./components/mares/MareConsultationComponent.vue').default},
				name: 'validation_fiches.consultation',
				meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Validation fiche'},
				children: [
					{
						path: '/validation_fiches/edit/:id',
						components: {third: require('./components/mares/MareFichesComponent.vue').default},
						name: 'validation_fiches.fiches',
						meta: {roles:['developpeur', 'gestionnaire','administrateur'], title: 'Validation fiche'},
					}
				]
			},
		]
	},

	/*
	 * Routes Recherche
	 */
	{
		path: '/recherche',
		component: require('./components/recherche/RechercheIndexComponent.vue').default,
		name:'recherche',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Recherche'}
	},
	{
		path: '/recherche/consultation/:id',
		component: require('./components/mares/MareConsultationComponent.vue').default,
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'},
		children: [
			{
				path: '/recherche/consultation/:id',
				components: {third: require('./components/mares/MareFichesComponent.vue').default},
				name: 'recherche.consultation',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur'], title: 'Consultation'},
			},
			{
				path: '/recherche/consultation/:id/nouvelle_fiche',
				components: {third: require('./components/mares/MareFicheComponent.vue').default},
				name: 'recherche.nouvelle_fiche',
				meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Consultation'}
			},
		]
	},



	/*
	 * Routes Exports
	 */
	{
		path: '/exports',
		component: require('./components/exports/ExportComponent.vue').default,
		name:'exports',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Exports'}
	},


	/******************************************* PARAMETRES*************************************************************/
	{path: '/parametres', component: require('./components/parametres/ParametresComponent.vue').default, name:'parametres', meta: {title: 'Autres paramètres'}},

		/*
		 * Utilisateur
		 */
		{path: '/utilisateurs', component: require('./components/parametres/utilisateurs/UtilisateursIndexComponent.vue').default, name:'utilisateurs', meta: {roles:['developpeur', 'administrateur'], title: 'Utilisateurs'}},
		{path: '/utilisateurs/create', component: require('./components/parametres/utilisateurs/UtilisateursCreateComponent.vue').default, name:'utilisateurs.create', meta: {roles:['developpeur', 'administrateur'], title: 'Ajouter un utilisateur'}},
		{path: '/utilisateurs/edit/:id', component: require('./components/parametres/utilisateurs/UtilisateursEditComponent.vue').default, name:'utilisateurs.edit', meta: {roles:['developpeur', 'administrateur'], title: 'Modifier un utilisateur'}},
		{path: '/password', component: require('./components/parametres/utilisateurs/PasswordComponent.vue').default, name:'password', meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Modifier mon mot de passe'}},


		{path: '/taxon_faunes', component: require('./components/parametres/taxons/TaxonIndexComponent.vue').default, name:'taxon_faunes', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon faune'}},
		{path: '/taxon_faunes/create', component: require('./components/parametres/taxons/TaxonCreateComponent.vue').default, name:'taxon_faunes.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon faune'}},
		{path: '/taxon_faunes/edit/:id', component: require('./components/parametres/taxons/TaxonEditComponent.vue').default, name:'taxon_faunes.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon faune'}},

		{path: '/taxon_flores', component: require('./components/parametres/taxons/TaxonIndexComponent.vue').default, name:'taxon_flores', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon flore'}},
		{path: '/taxon_flores/create', component: require('./components/parametres/taxons/TaxonCreateComponent.vue').default, name:'taxon_flores.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon flore'}},
		{path: '/taxon_flores/edit/:id', component: require('./components/parametres/taxons/TaxonEditComponent.vue').default, name:'taxon_flores.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon flore'}},

		{path: '/taxon_eee_faunes', component: require('./components/parametres/taxons/TaxonIndexComponent.vue').default, name:'taxon_eee_faunes', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE faune'}},
		{path: '/taxon_eee_faunes/create', component: require('./components/parametres/taxons/TaxonCreateComponent.vue').default, name:'taxon_eee_faunes.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE faune'}},
		{path: '/taxon_eee_faunes/edit/:id', component: require('./components/parametres/taxons/TaxonEditComponent.vue').default, name:'taxon_eee_faunes.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE faune'}},

		{path: '/taxon_eee_flores', component: require('./components/parametres/taxons/TaxonIndexComponent.vue').default, name:'taxon_eee_flores', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE flore'}},
		{path: '/taxon_eee_flores/create', component: require('./components/parametres/taxons/TaxonCreateComponent.vue').default, name:'taxon_eee_flores.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE flore'}},
		{path: '/taxon_eee_flores/edit/:id', component: require('./components/parametres/taxons/TaxonEditComponent.vue').default, name:'taxon_eee_flores.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Taxon EEE flore'}},

		{path: '/faunes', component: require('./components/parametres/especes/EspeceIndexComponent.vue').default, name:'faunes', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Faunes'}},
		{path: '/faunes/create', component: require('./components/parametres/especes/EspeceCreateComponent.vue').default, name:'faunes.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Faunes'}},
		{path: '/faunes/edit/:id', component: require('./components/parametres/especes/EspeceEditComponent.vue').default, name:'faunes.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Faunes'}},

		{path: '/flores', component: require('./components/parametres/especes/EspeceIndexComponent.vue').default, name:'flores', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Flores'}},
		{path: '/flores/create', component: require('./components/parametres/especes/EspeceCreateComponent.vue').default, name:'flores.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Flores'}},
		{path: '/flores/edit/:id', component: require('./components/parametres/especes/EspeceEditComponent.vue').default, name:'flores.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Flores'}},

		{path: '/eee_faunes', component: require('./components/parametres/especes/EspeceIndexComponent.vue').default, name:'eee_faunes', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Faunes'}},
		{path: '/eee_faunes/create', component: require('./components/parametres/especes/EspeceCreateComponent.vue').default, name:'eee_faunes.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Faunes'}},
		{path: '/eee_faunes/edit/:id', component: require('./components/parametres/especes/EspeceEditComponent.vue').default, name:'eee_faunes.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Faunes'}},


		{path: '/eee_flores', component: require('./components/parametres/especes/EspeceIndexComponent.vue').default, name:'eee_flores', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Flores'}},
		{path: '/eee_flores/create', component: require('./components/parametres/especes/EspeceCreateComponent.vue').default, name:'eee_flores.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Flores'}},
		{path: '/eee_flores/edit/:id', component: require('./components/parametres/especes/EspeceEditComponent.vue').default, name:'eee_flores.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'EEE Flores'}},

		{path: '/groupes', component: require('./components/parametres/groupes/GroupeIndexComponent.vue').default, name:'groupes', meta: {roles:['developpeur', 'administrateur'], title: 'Groupes'}},
		{path: '/groupes/create', component: require('./components/parametres/groupes/GroupeCreateComponent.vue').default, name:'groupes.create', meta: {roles:['developpeur', 'administrateur'], title: 'Groupes'}},
		{path: '/groupes/edit/:id', component: require('./components/parametres/groupes/GroupeEditComponent.vue').default, name:'groupes.edit', meta: {roles:['developpeur', 'administrateur'], title: 'Groupes'}},

		{path: '/formulaires', component: require('./components/parametres/formulaires/FormulaireIndexComponent.vue').default, name:'formulaires', meta: {roles:['developpeur', 'administrateur'], title: 'Formulaires'}},
		{path: '/formulaires/edit/:id', component: require('./components/parametres/formulaires/FormulaireEditComponent.vue').default, name:'formulaires.edit', meta: {roles:['developpeur', 'administrateur'], title: 'Formulaires'}},

		{path: '/observateurs', component: require('./components/parametres/observateurs/ObservateurIndexComponent.vue').default, name:'observateurs', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Observateurs'}},
		{path: '/observateurs/create', component: require('./components/parametres/observateurs/ObservateurCreateComponent.vue').default, name:'observateurs.create', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Observateurs'}},
		{path: '/observateurs/edit/:id', component: require('./components/parametres/observateurs/ObservateurEditComponent.vue').default, name:'observateurs.edit', meta: {roles:['developpeur', 'gestionnaire', 'administrateur'], title: 'Observateurs'}},

		{path: '/configuration_generales', component: require('./components/parametres/configuration_generales/ConfigurationGeneraleComponent.vue').default, name:'configuration_generales', meta: {roles:['developpeur', 'administrateur'], title: 'Configuration Générale'}},


		{path: '/configuration_logo_partenaires', component: require('./components/parametres/configuration_logo_partenaires/ConfigurationLogoPartenaireComponent.vue').default, name:'logo_partenaires', meta: {roles:['developpeur', 'administrateur'], title: 'Logo partenaire'}},
		{path: '/configuration_logo_partenaires/create', component: require('./components/parametres/configuration_logo_partenaires/ConfigurationLogoPartenaireCreateComponent.vue').default, name:'logo_partenaires.create', meta: {roles:['developpeur', 'administrateur'], title: 'Logo partenaire'}},
		{path: '/configuration_logo_partenaires/edit/:id', component: require('./components/parametres/configuration_logo_partenaires/ConfigurationLogoPartenaireEditComponent.vue').default, name:'logo_partenaires.edit', meta: {roles:['developpeur', 'administrateur'], title: 'Logo partenaire'}},


	/******************************************* PARAMETRES*************************************************************/

	/*
	 * Assistances
	 */
	{
		path: '/aides',
		component: require('./components/assistances/aide/AidesComponent.vue').default,
		name:'aides',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Aides'}
	},
	{
		path: '/ameliorations',
		component: require('./components/assistances/amelioration/AmeliorationsComponent.vue').default,
		name:'ameliorations',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Améliorations'}
	},
	{
		path: '/tickets',
		component: require('./components/assistances/tickets/TicketsComponent.vue').default,
		name:'tickets',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'administrateur'], title: 'Tickets'}
	},
	/*
	 * Page errors
	 */
	{
		path: '/errors/404',
		component: require('./components/errors/Error404Component.vue').default,
		name:'error.404',
		meta: {roles:['developpeur', 'gestionnaire', 'utilisateur', 'visiteur', 'administrateur']}
	},
	{
		path: '*',
		component: require('./components/errors/Error404Component.vue').default
	}
]