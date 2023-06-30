<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilisateur;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Departement;
use App\Intercommunalite;
use App\Mare;
use App\Fiche;
use App\Commune;
use App\Commentaire;
use App\DemandeCompte;
use App\ConfigLogoPartenaire;

use App\TaxFauneGroupe;
use App\ConfigGeneral;

/*
 * Demande de création de compte, pas de mail qui s'envoi
 * exception pour les mares de lorraines, a envoyer a administrateur
 *  Suppression user impossible
 *
 *
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * Test commit transfert repo
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Salut à vous David et aGEEKculteur
     * Si vous lisez ça, petite explication :
     * RDV dans routes => web.php vous trouverez ici toutes les routes BACKEND
     * Lorsque l'utilisateur arrive sur le site (url '/'), on appelle le controller HomeController avec la méthode index (méthode juste en dessous de mon monologue)
     * Cette fonction va retourner la vue home (qui se trouve dans :  resources => home.blade.php, blade étant le moteur de template de LARAVEL, comme twig sur
     * Symfony)
     * Sur la vue home, vous trouverez le système de "routing" de VueJs
     * Comme je génère les vues en JS, toutes les vues front sont appelés grâce à Vue
     * Vous vous en doutez donc, il y a un autre fichier de routes pour générer le front, que vous trouverez dans resources => js => app.js
     * Toutes les vues se trouvent dans des "composants", dans le dossier RESOURCES => JS => COMPONENTS
     * Avec VueJs, 1 vue = 1 composant (ou plusieurs composants enfants si on doit en réutiliser, pour éviter la duplication de code) et le HTML/JS se trouvent dans le même * fichier, pour une meilleure maintenabilité
     */
    public function index()
    {


        // echo "L'application PRAM est en maintenance, merci de revenir plus tard";

        // die();

        $user = Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
                ->where('utilisateurs.id', '=', \Auth::id())
                ->select('utilisateurs.nom as nom', 'utilisateurs.prenom as prenom', 'utilisateurs.email as email', 'r.nom_interne as role', 'utilisateurs.actif as actif')
                ->first();

        if(!Cache::get('cache_config_generale')){
            $config = ConfigGeneral::first();
            $partenaires = ConfigLogoPartenaire::all();
            $config->partenaires = $partenaires;
            $seconds = 14400; // 4heures de cache
            Cache::put('cache_config_generale', $config, $seconds);
        }else{
            $config = Cache::get('cache_config_generale');
        }

        if($user){
            $user->authenticated = auth()->check();
            $user->base_url= url('/');
            $user->login_path = Route('login');
            $user->logout_path = url('logout');

            if($user->role == 'developpeur'){
                $users = Utilisateur::orderby('nom', 'asc')->get();
                return view('home', compact('user', 'users', 'config'));
            }

        }else{
            $user = collect([
                'id' => 0,
                'nom' => '',
                'prenom' => '',
                'email' => '',
                'role' => 'visiteur',
                'authenticated' => false,
                'base_url' => url('/'),

            ]);

            $user = json_encode($user);
        }
        
        return view('home', compact('user', 'config'));
    }


    public function getUrl(Request $request)
    {
        $change_user = url('change_user');
        $clear_cache = '';
        $admin_id = '';
        $users = [];
        $last_connexions = [];
        $import_communes = '';
        $import_sites = '';
        $import_commune_site = '';
        $import_intitules_postes = '';
        $version_app = config('app.version');
        $nom_logo = env('APP_LOGO', 'logo.png');

        $logo = asset('images/logo/'.$nom_logo);

        if(\Auth::user() && \Auth::user()->hasRole('developpeur')){
            $clear_cache = url('developpeur/clear-cache');
            $users = Utilisateur::with('role')->orderby('nom', 'asc')->get();
        }else{
            // $users = Utilisateur::where('actif', 1)->select('id', 'nom', 'prenom')->get();
            $user = [];
        }

        if(!empty($request->session()->get('admin_id'))){
            $admin_id = $request->session()->get('admin_id');
        }

        $config = ConfigGeneral::first();

        return response()->json(compact('clear_cache', 'users', 'last_connexions', 'change_user', 'admin_id', 'version_app', 'logo', 'config'));
    }



    /*
     * Permet aux développeur le changement de user
     */
    public function change_user(Request $request)
    {
        $user = \Auth::user();

        if($user->hasRole('developpeur')){
            $admin_id = $user->id;
            $request->session()->put('admin_id', $admin_id);

            $id = $request->id;
            \Auth::loginUsingId($id);
        }else{
            $id = $request->id;
            \Auth::loginUsingId($id);
            session()->forget('admin_id');
        }

        $url = url('/');
        return response()->json(compact('url'));
    }


    /*
     * Récupère toutes les stats des mares pour l'affichage dans le widget
     */
    public function widgetStatistiquesMares()
    {
        $mares = Mare::leftjoin('caracterisations as c', 'c.id', '=', 'mares.caracterisation_id')
                ->select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN c.nom_interne = 'en_attente_de_validation' THEN 1 ELSE 0 END) AS total_en_attente_de_validation"),
                    DB::raw("SUM(CASE WHEN c.nom_interne = 'vue' THEN 1 ELSE 0 END) AS total_vue"),
                    DB::raw("SUM(CASE WHEN c.nom_interne = 'caracterisee' THEN 1 ELSE 0 END) AS total_caracterisee"),
                    DB::raw("SUM(CASE WHEN c.nom_interne = 'disparue' THEN 1 ELSE 0 END) AS total_disparue"),
                    DB::raw("SUM(CASE WHEN c.nom_interne = 'potentielle' THEN 1 ELSE 0 END) AS total_potentielle")
                )
                ->first();
        return response()->json(compact('mares'));
    }


    /*
     * Récupère les mares du user connecte
     */
    public function widgetStatistiquesMesMares()
    {
        $user = Utilisateur::find(\Auth::id());
        // return var_dump($user->observateur->id);

        $mares = Mare::where('utilisateur_id', '=', $user->id)
                ->when($user->observateur != null && $user->observateur->id, function($query) use($user){
                    $query->orWhere('observateur_id', '=', $user->observateur->id);
                })
                ->leftjoin('statuts as s', 's.id', '=', 'mares.statut_id')
                ->select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN s.nom_interne = 'en_attente_de_validation' THEN 1 ELSE 0 END) as total_en_attente")
                )
                ->first();

        $notifications = Commentaire::where('utilisateur_vu', '=', false)
                        ->whereHas('mare', function($query) use($user){
                            $query
                            ->where('utilisateur_id', '=', \Auth::id())
                            ->when($user->observateur != null && $user->observateur->id, function($q) use($user){
                                $q->orWhere('observateur_id', '=', $user->observateur->id);
                            });
                        })
                        ->count();

        return response()->json(compact('mares', 'notifications'));
    }


    /*
     * Récupère les fiches du user connecte
     */
    public function widgetStatistiquesMesFiches()
    {
        $user = Utilisateur::find(\Auth::id());

        $fiches =   Fiche::where('fiches.utilisateur_id', '=', $user->id)
                    ->whereHas('mare', function($query){
                        $query->whereHas('statut', function($q){
                            $q->where('nom_interne', '=', 'valider');
                        });
                    })
                    ->when($user->observateur != null && $user->observateur->id, function($query) use($user){
                        $query  
                        ->orWhere('fiches.observateur_id', '=', $user->observateur->id)
                        ->whereHas('mare', function($q){
                            $q->whereHas('statut', function($req){
                                $req->where('nom_interne', '=', 'valider');
                            });
                        });
                    })
                    ->leftjoin('statuts as s', 's.id', '=', 'fiches.statut_id')
                    ->select(
                        DB::raw("COUNT(*) as total"),
                        DB::raw("SUM(CASE WHEN s.nom_interne = 'en_attente_de_validation' THEN 1 ELSE 0 END) as total_en_attente")
                    )
                    ->first();

        $notifications = Commentaire::where('utilisateur_vu', '=', false)
                        ->whereHas('fiche', function($query){
                            $query->where('utilisateur_id', '=', \Auth::id());
                        })
                        // ->whereHas('statut', function($query){
                        //     $query->where('nom_interne', '!=', 'valider');
                        // })
                        ->count();

        return response()->json(compact('fiches', 'notifications'));
    }


    /*
     * Récupère les mares, fiches et les comptes à valider
     */
    public function widgetAdminGestionnaire()
    {
        $user = Utilisateur::find(\Auth::id());

        $departements_user = [];

        if($user->departements){
            foreach ($user->departements as $dep) {
                $departements_user[] = $dep->code_insee;
            }
        }

        $mares = Mare::when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($query) use($departements_user){
                        $query->whereIn('departement_code', $departements_user);
                    })
                    ->whereHas('statut', function($query){
                        $query->where('nom_interne', '=', 'en_attente_de_validation');
                    })
                    // ->where('valide', '=', false)
                    ->count();

        $notifications_mares = Commentaire::where('validateur_vu', '=', false)
                                ->whereHas('mare', function($query) use($departements_user, $user){
                                    $query
                                    ->whereHas('statut', function($q){
                                        $q->where('nom_interne', '=', 'en_attente_de_validation');
                                    })
                                    ->when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($q) use($departements_user){
                                        $q->whereIn('departement_code', $departements_user);
                                    });
                                })
                                ->count();

        $notifications_fiches = Commentaire::where('validateur_vu', '=', false)
                                ->whereHas('fiche', function($query) use($departements_user, $user){
                                    $query
                                    ->join('mares as m', 'm.id', '=', 'fiches.mare_id')
                                    ->whereHas('statut', function($q){
                                        $q->where('nom_interne', '=', 'en_attente_de_validation');
                                    })
                                    ->when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($q) use($departements_user){
                                        $q->whereIn('m.departement_code', $departements_user);
                                    });
                                })
                                ->count();

        $fiches = Fiche::when($user->hasRole('gestionnaire') || $user->hasRole('administrateur'), function($query) use($departements_user){
                        $query->whereHas('mare', function($q) use($departements_user){
                            $q->whereIn('mares.departement_code', $departements_user);
                        });
                    })
                    ->whereHas('statut', function($query){
                        $query->where('nom_interne', 'en_attente_de_validation');
                    })
                    ->whereHas('mare', function($query){
                        $query->whereHas('statut', function($q){
                            $q->where('nom_interne', '=', 'valider');
                        });
                    })
                    ->select('fiches.id')
                    ->count();

        // $fiches = Fiche::join('mares as m', 'm.id', '=', 'fiches.mare_id')
        //             ->when($user->hasRole('gestionnaire'), function($query) use($departements_user){
        //                 $query->whereIn('m.departement_code', $departements_user);
        //             })
        //             ->whereHas('statut', function($q){
        //                 $q->where('nom_interne', '=', 'en_attente_de_validation');
        //             })
        //             ->whereHas('mare', function($query){
        //                 $query->whereHas('statut', function($q){
        //                     $q->where('nom_interne', '=', 'en_attente_de_validation');
        //                 });
        //             })
        //             // ->where('m.valide', '=', true)
        //             ->select('fiches.*')
        //             ->count();

        $comptes = DemandeCompte::where('valide', false)->count();

        return response()->json(compact('mares', 'fiches', 'comptes', 'notifications_mares', 'notifications_fiches'));

    }



    public function dashboardUtilisateur()
    {

        // $leads_par_service = Lead::
        //         leftjoin('sites as s', 's.id', '=', 'leads.site_id')
        //         ->leftjoin('services as se', 'se.id', 'leads.service_id')
        //         ->leftjoin('statuts as st', 'st.id', 'leads.statut_id')
        //         ->whereIn('source_type_id', $source_type)
        //         ->where('statut_id', '<>', Lead::STATUT_ERREUR_ID)
        //         ->where('statut_id', '<>', Lead::STATUT_DOUBLON_ID)
        //         ->where('date_reception', '>=', $debut)
        //         ->where('date_reception', '<=', $fin)
        //         ->select(
        //             's.id as site_id',
        //             DB::raw("s.nom AS nom"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vn' AND st.nom_interne = 'vente' THEN 1 ELSE 0 END)*100 /
        //                     SUM(CASE WHEN se.nom_interne = 'vn' THEN 1 ELSE 0 END) AS taux_transformation_vn"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vo' AND st.nom_interne = 'vente' THEN 1 ELSE 0 END)*100 /
        //                     SUM(CASE WHEN se.nom_interne = 'vo' THEN 1 ELSE 0 END) AS taux_transformation_vo"),
        //             DB::raw("SUM(CASE WHEN st.nom_interne = 'vente' THEN 1 ELSE 0 END)*100 /
        //                     COUNT(*) AS taux_transformation_total"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vn' AND st.nom_interne = 'vente' THEN 1 ELSE 0 END) AS total_vente_vn"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vn' THEN 1 ELSE 0 END) AS total_vn"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vo' AND st.nom_interne = 'vente' THEN 1 ELSE 0 END) AS total_vente_vo"),
        //             DB::raw("SUM(CASE WHEN se.nom_interne = 'vo' THEN 1 ELSE 0 END) AS total_vo")
        //         )
        //         ->groupby('s.id', 'nom')
        //         ->get();

    }


}