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
use App\Commune;
use App\ConfigGeneral;

class CarteController extends Controller
{
    /*
     * Charge les données nécessaires à la carte
     * Pour l'instant les données sont en dur, mais il faut imaginer qu'elles viennent de la base de données
     * C'est AXIOS qui permet d'appeler cette fonction (ligne 154 du composant CarteComponent dans resources => js => components)
     */
    public function loadCarteData()
    {        
        if(!Cache::get('cache_carte_data')){
            $config = ConfigGeneral::first();
            $departements = Departement::orderby('nom', 'asc')->get();

            // test occitanie : 43.796707, 2.155287


            if(!empty($config->path_region_geojson)){
                $grand_est_geo_json = json_decode(file_get_contents(public_path('configuration/region_geojson/'.$config->path_region_geojson)));
            }else{
                $grand_est_geo_json = json_decode(file_get_contents(public_path('json/region_grand_est.geojson')));
            }

            $data = [
                'departements' => $departements,
                'grand_est_geo_json' => $grand_est_geo_json,
            ];

            $seconds = 300; // 5 minutes
            Cache::put('cache_carte_data', $data, $seconds);
        }else{
            $cache = Cache::get('cache_carte_data');
            $departements = $cache['departements'];
            $grand_est_geo_json = $cache['grand_est_geo_json'];
        }


        $mares = [];
        $total_mares = Mare::select('id', 'statut_id')
                        ->whereHas('statut', function($query){
                            $query->where('nom_interne', '=', 'valider');
                        })->count();

        return response()->json(compact('departements','grand_est_geo_json', 'mares', 'total_mares'));

    }


    public function loadAllMares()
    {
        $mares = Mare::leftjoin('caracterisations as c', 'c.id', '=', 'mares.caracterisation_id')
                ->where([
                    ['lng', '!=', null],
                    ['lat', '!=', null],
                    // ['valide', '=', true]
                ])
                ->whereHas('statut', function($query){
                    $query->where('nom_interne', '=', 'valider');
                })
                ->select('mares.id as id', 'mares.nom as nom', 'mares.lat as latitude', 'mares.lng as longitude', 'mares.nom as type_principale', 'c.couleur as couleur')
                ->get();

        return response()->json(compact('mares'));
    }


    /*
     * Charge les intercommunalites en fonction du departement selectionné
     * Récupère le centroide et le contour du departement
     */
    public function loadIntercommunaliteFromDepartement($id, $load_mares)
    {
        $departement = Departement::find($id);
        $data = [];
        $centroide = [];
        $geojson = [];
        $mares = [];

        if($departement){
            /* 
             * Charge le centroide et le contour du département séléctionné
             */
            $centroide = getCentroid('departements', 'code_insee', $departement->code_insee);
            $geojson = getGeoJsonFromSelectedFilter('departements', 'code_insee', $departement->code_insee);

            /*
             * Charge les intercommunalités du département séléctionné
             */
            $data = DB::table('epcitab_ge as tab')
            ->join('intercommunalites as i', 'i.siren', '=', 'tab.siren')
            ->where('dep_epci', '=', "$departement->code_insee")
            ->select('tab.siren', 'tab.raison_soc', 'i.id')
            ->orderby('tab.raison_soc', 'asc')
            ->distinct('tab.siren', 'tab.raison_soc')
            ->get();


            if($load_mares === 'false'){
                $mares = Mare::leftjoin('caracterisations as c', 'c.id', '=', 'mares.caracterisation_id')
                ->where([
                    ['lng', '!=', null],
                    ['lat', '!=', null],
                    // ['valide', '=', true],
                    ['departement_code', '=', $departement->code_insee]
                ])
                ->whereHas('statut', function($query){
                    $query->where('nom_interne', '=', 'valider');
                })
                ->select('mares.id as id', 'mares.nom as nom', 'mares.lat as latitude', 'mares.lng as longitude', 'mares.nom as type_principale', 'c.couleur as couleur')
                ->get();
            }

            $total_mares = Mare::where('departement_code', $departement->code_insee)->count();
        }

        return response()->json(compact('data', 'geojson', 'centroide', 'mares', 'total_mares'));
    }

    public function loadCommuneFromDepartement($id, $load_mares)
    {
        $departement = Departement::find($id);
        $data = [];
        $centroide = [];
        $geojson = [];

        if($departement){
            $code_insee = substr($departement->code_insee, 0, 2);
            /*
             * Charge les communes du département sélectionné
             */
            $data = Commune::select('id', 'insee', 'nom')
                        ->where('insee', 'like', $code_insee."%")
                        ->orderby('nom', 'asc')
                        ->get();
        }

        return response()->json(compact('data'));
    }



    /*
     * Charge les communes en fonction de l'interco selectionné
     * Récupère le centroide et le contour de l'interco
     */
    public function loadCommuneFromIntercommunalite($id, $load_mares){
        $intercommunalite = Intercommunalite::find($id);
        $data = [];
        $centroide = [];
        $geojson = [];


        if($intercommunalite){
            /* 
             * Charge le centroide et le contour de l'intercommunalité séléctionné
             */
            $centroide = getCentroid('intercommunalites', 'siren', $intercommunalite->siren);
            $geojson = getGeoJsonFromSelectedFilter('intercommunalites', 'siren', $intercommunalite->siren);

            /*
             * Charge les communes de l'intercommunalité séléctionné
             */
            $data = DB::table('epcitab_ge as tab')
            ->join('communes as c', 'c.insee', '=', 'tab.insee')
            ->where('siren', '=', "$intercommunalite->siren")
            ->select('c.insee', 'c.nom', 'c.id')
            ->orderby('c.nom', 'asc')
            ->distinct('c.insee', 'c.nom')
            ->get();
 

            $total_mares = Mare::where('intercommunalite_siren', $intercommunalite->siren)
                            ->whereHas('statut', function($query){
                                $query->where('nom_interne', '=', 'valider');
                            })
                            ->count();


        }

        return response()->json(compact('data', 'centroide', 'geojson', 'total_mares'));
    }



    /*
     * Charge les mares en fonction de la commune selectionnée
     * Récupère le centroide et le contour de la commune
     */
    public function loadMareFromCommune($id, $load_mares){
        $commune = Commune::find($id);
        $data = [];
        $centroide = [];
        $geojson = [];

        if($commune){
            /* 
             * Charge le centroide et le contour de la commune séléctionné
             */
            $centroide = getCentroid('communes', 'insee', strval($commune->insee));
            $geojson = getGeoJsonFromSelectedFilter('communes', 'insee', strval($commune->insee));

            /*
             * Charge les mares de la commune séléctionné
             */
            // $data = getChildFilter('communes', 'intercommunalites', 'num_fiscalite', $intercommunalite->num_fiscalite, 'nom');
            // $query = "SELECT id as id, nom as type_principale, nom as nom, lng as longitude, lat as latitude FROM pram.mares AS age,
            //         (SELECT geom FROM pram.communes WHERE insee = '$commune->insee') AS bge
            //         WHERE public.ST_Intersects(age.geom, bge.geom)
            //         ORDER BY age.id ASC";

            // $data = DB::select(DB::raw($query));

            $data = Mare::where('commune_insee', $commune->insee)
                    ->whereHas('statut', function($query){
                        $query->where('nom_interne', '=', 'valider');
                    })->get();

            $total_mares = $data->count();


        }

        return response()->json(compact('centroide', 'geojson', 'data', 'total_mares'));
    }


    
    /*
     * Charge les mares de façon asynchrone
     * Et en fonction soit du departement, de l'interco ou de la commune
     * Si click sur toutes les amres, on charge toutes les mares
     */
    public function loadMares($id)
    {
        $mare = Mare::find($id);
        $data = [];
        $centroide = [];
        $geojson = [];

        if($mare){
            /* 
             * Charge le centroide et le contour de la commune séléctionné
             */
            $centroide = ['coord_y' => $mare->lat, 'coord_x' => $mare->lng];
            // $geojson = getGeoJsonFromSelectedFilter('mares', 'id', strval($mare->id));
            $geojson = [];
        }

        return response()->json(compact('centroide', 'geojson', 'data'));

    }

}
