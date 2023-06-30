<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

/*
 * Redirige et affiche un message d'incompatibilité si internet explorer
 */
Route::get('internet_explorer', function(){
	return view('internet_explorer');
});

/*
 * Routes cartes
 */

Route::get('carte/load_all_mares', 'CarteController@loadAllMares')->name('carte.load_all_mares');
	
Route::get('carte/load_carte_data', 'CarteController@loadCarteData')->name('carte.load_carte_data');
Route::get('carte/load_intercommunalite_from_departement/{id}/{load_mares}', 'CarteController@loadIntercommunaliteFromDepartement')->name('carte.load_intercommunalite_from_departement');

Route::get('carte/load_commune_from_departement/{id}/{load_mares}', 'CarteController@loadCommuneFromDepartement')->name('carte.load_commune_from_departement');
Route::get('carte/load_commune_from_intercommunalite/{id}/{load_mares}', 'CarteController@loadCommuneFromIntercommunalite')->name('carte.load_commune_from_intercommunalite');

Route::get('carte/load_mare_from_commune/{id}/{load_mares}', 'CarteController@loadMareFromCommune')->name('carte.load_mare_from_commune');
Route::get('carte/load_mares/{id}/{load_mares}', 'CarteController@loadMares')->name('carte.load_mares');


/*
 * Création/edition/consultation des mares
 */
Route::post('mares/save_mare_after_drag', 'MareController@saveMareAfterDrag')->name('mares.save_mare_after_drag');
Route::post('mares/find_info_mare', 'MareController@findInfoMare')->name('carte.find_info_mare');
Route::resource('mares', 'MareController');


/*
 * Demande de création de comptes
 */
Route::resource('demande_comptes', 'DemandeCompteController');
Route::get('/get_url', 'HomeController@getUrl')->name('get_url');



/*************************************************************************************************************************************
 * Routes qui nécessitent une connexion
 *************************************************************************************************************************************/
Route::group(['middleware' => ['auth', 'web']], function(){
	/*
	 * Routes dashboard
	 */
	Route::post('/dashboard/delete_log', 'DeveloppeurController@deleteLog')->middleware('can:developpeur,App\Role');
	Route::get('/dashboard/logs', 'DeveloppeurController@logs')->middleware('can:developpeur,App\Role');

	Route::get('/dashboard/utilisateur', 'HomeController@dashboardUtilisateur')->name('dashboard.utilisateur');


	/*
	 * Widgets
	 */
	Route::get('widget_statistiques_mares', 'HomeController@widgetStatistiquesMares')->name('widget_statistiques_mares');
	Route::get('widget_statistiques_mes_mares', 'HomeController@widgetStatistiquesMesMares')->name('widget_statistiques_mes_mares');
	Route::get('widget_statistiques_mes_fiches', 'HomeController@widgetStatistiquesMesFiches')->name('widget_statistiques_mes_fiches');
	Route::get('widget_admin_gestionnaire', 'HomeController@widgetAdminGestionnaire')->name('widget_admin_gestionnaire');



	/*
	 * Routes mes mares (mes mares index, mes mares en attentes, mes fiches en attente)
	 */
	Route::post('mes_mares/save_code_ofb', 'MesMareController@saveCodeOfb')->name('mes_mares.save_code_ofb');

	Route::match(['put', 'post'], 'fiches_attentes/update/{id}', 'MesMareController@updateFicheAttente')->name('fiche_attente.update');
	
	Route::get('fiches_attentes/edit/{id}', 'MesMareController@editFicheAttente')->name('fiche_attente.edit');
	
	Route::post('mes_fiches/toutes_mes_fiches', 'MesMareController@toutesMesFiches')->name('mes_fiches.toutes_mes_fiches');
	Route::post('mes_fiches/fiches_du_groupe', 'MesMareController@fichesDuGroupe')->name('mes_fiches.fiches_du_groupe');
	
	Route::post('mes_fiches/update_commentaire_lu', 'MesMareController@updateCommentaireLu')->name('mes_fiches.update_commentaire_lu');
	Route::post('mes_mares/update_commentaire_lu', 'MesMareController@updateCommentaireLu')->name('mes_mares.update_commentaire_lu');

	Route::post('mes_fiches/send_commentaire', 'MesMareController@sendCommentaire')->name('mes_fiches.send_commentaires');
	Route::post('mes_mares/send_commentaire', 'MesMareController@sendCommentaire')->name('mes_mares.send_commentaires');

	Route::post('mes_mares/toutes_mes_mares', 'MesMareController@toutesMesMares')->name('mes_mares.toutes_mes_mares');
	Route::post('mes_mares/mares_du_groupe', 'MesMareController@maresDuGroupe')->name('mes_mares.mares_du_groupe');

	Route::get('mes_fiches', 'MesMareController@index')->name('mes_fiches.index');

	Route::delete('mes_fiches/{id}', 'MesMareController@destroyFiche')->name('mes_fiches.destroy');
	Route::resource('mes_mares', 'MesMareController');


	/*
	 * Validation des fiches
	 */
	Route::resource('validation_fiches', 'ValidationFicheController')->middleware('can:access_validation_mares_fiches,App\Role');


	/*
	 * Validation des mares
	 */
	Route::post('validation_fiches/update_commentaire_lu', 'ValidationMareController@updateCommentaireLu')->name('validation_fiches.update_commentaire_lu');
	Route::post('validation_fiches/send_commentaire', 'ValidationMareController@sendCommentaire')->name('validation_fiches.send_commentaires');

	Route::post('validation_mares/update_commentaire_lu', 'ValidationMareController@updateCommentaireLu')->name('validation_mares.update_commentaire_lu');
	Route::post('validation_mares/send_commentaire', 'ValidationMareController@sendCommentaire')->name('validation_mares.send_commentaires');
	Route::resource('validation_mares', 'ValidationMareController')->middleware('can:access_validation_mares_fiches,App\Role');


	/*
	 * Route recherche
	 */
	Route::post('recherche/get_mares_by_identifiant', 'RechercheController@getMaresByIdentifiant')->name('recherche.get_mares_by_identifiant');
	Route::post('recherche/get_mares_by_departement', 'RechercheController@getMaresByDepartement')->name('recherche.get_mares_by_departement');
	Route::post('recherche/get_mares_by_intercommunalite', 'RechercheController@getMaresByIntercommunalite')->name('recherche.get_mares_by_intercommunalite');
	Route::post('recherche/get_mares_by_commune', 'RechercheController@getMaresByCommune')->name('recherche.get_mares_by_commune');
	Route::get('recherche/search_mare', 'RechercheController@searchMare')->name('recherche.search_mare');
	Route::get('recherche/search_mare_by_code_ofb', 'RechercheController@searchMarebyCodeOfb')->name('recherche.search_mare_by_code_ofb');
	Route::resource('recherche', 'RechercheController');

	/*
	 * Assistance
	 */
	Route::post('create_tickets', 'AssistanceController@createTicket')->name('create_tickets');


	/*
	 * Routes Exports
	 */
	Route::get('exports/index', 'ExportController@index')->name('exports.index');
	Route::post('exports/exports', 'ExportController@export')->name('exports.exports');
	Route::get('exports/fiche_pdf/{id}', 'ExportController@fichePdf');
	Route::post('exports/exports_utilisateurs', 'ExportController@exportUtilisateurs')->name('exports.exports_utilisateurs');
	Route::get('download/{file}', 'ExportController@download');
	Route::get('download_excel/{file}', 'ExportController@downloadExcel');


	/* 
	 * Routes Paramètres
	 */
	Route::post('utilisateurs/check_if_observateur_exist', 'UtilisateurController@checkIfObservateurExist')->name('utilisateurs.check_if_observateur_exist');
	Route::match(['put', 'post'], 'password', 'UtilisateurController@updatePassword');
	Route::resource('utilisateurs', 'UtilisateurController');

	Route::group(['middleware' => 'can:access_parametres,App\Role'], function() {
		Route::resource('taxon_faunes', 'TaxonController');
		Route::resource('taxon_flores', 'TaxonController');
		Route::resource('taxon_eee_faunes', 'TaxonController');
		Route::resource('taxon_eee_flores', 'TaxonController');

		Route::resource('faunes', 'EspeceController');
		Route::resource('flores', 'EspeceController');
		Route::resource('eee_faunes', 'EspeceController');
		Route::resource('eee_flores', 'EspeceController');

		Route::resource('groupes', 'GroupeController');
		Route::resource('observateurs', 'ObservateurController');
		Route::resource('formulaires', 'FormulaireController');
	});

	Route::get('configuration/configurations_generales', 'ConfigurationController@configurationGenerale');
	Route::post('configuration/update_configurations_generales', 'ConfigurationController@updateConfigurationGenerale');
	Route::resource('configuration_logo_partenaires', 'ConfigurationLogoPartenaireController');



	/*
	 * Routes développeurs
	 */
	Route::post('developpeur/write_javacript_log', 'DeveloppeurController@writeJsLogs')->name('developpeur.write_javacript_log');
	Route::post('change_user', 'HomeController@change_user');
	Route::get('developpeur/generateObservateur', 'DeveloppeurController@createCopieUserToObservateur')->middleware('can:developpeur,App\Role');
	Route::get('developpeur/clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return redirect('/');
    })->middleware('can:developpeur,App\Role');

    
	// Route::get('developpeur/import_mares', 'ImportController@importMares')->name('developpeur.import_mares');
	Route::get('developpeur/import_auteurs', 'ImportController@importAuteurs')->name('developpeur.import_auteurs');
	Route::get('developpeur/affect_observateur_to_mare', 'ImportController@affectObservateurToMare')->name('developpeur.affect_observateur_to_mare');
});