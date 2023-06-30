<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departement;
use App\Intercommunalite;
use App\Commune;
use App\Utilisateur;
use App\Mare;
use App\Traits\MareTrait;
use Illuminate\Support\Facades\Cache;

class RechercheController extends Controller
{
    use MareTrait;


    /*
     * Affiche l'index de la page recherche
     */
    public function index()
    {

        if(!Cache::get('cache_module_recherche')){

            $departements = Departement::select('id', 'code_insee', 'nom')
                        ->orderby('nom', 'asc')
                        ->get();

            $intercommunalites = Intercommunalite::select('id', 'siren', 'raison_soc')
                                ->orderby('raison_soc', 'asc')
                                ->get();

            $communes = Commune::select('id', 'insee', 'nom')
                        ->selectRaw("CONCAT(nom,' ',insee) as label")
                        ->orderby('nom', 'asc')
                        ->get();

            $types = [
                ['nom' => 'Identifiant de mare', 'nom_interne' => 'identifiant_de_mare'],
                ['nom' => 'Departements', 'nom_interne' => 'departements'],
                ['nom' => 'Intercommunalités', 'nom_interne' => 'intercommunalites'],
                ['nom' => 'Communes', 'nom_interne' => 'communes']
            ];

            $seconds = 14400; // 4 heures pour le cache
            $data = compact('departements', 'intercommunalites', 'communes', 'types');
            
            Cache::put('cache_module_recherche', $data, $seconds);
        }else{
            $data = Cache::get('cache_module_recherche');
        }

        return response()->json($data);
    }



    /*
     * Permet la recherche rapide d'un utilisateur dans la base de données communes
     */
    public function searchMare(Request $request)
    {
        $term = $request->q;
        $mares = [];

        extract($this->checkGroupeUser());

        $m = 
        Mare::
        when(!empty($request->type) && $request->type === 'code_ofb', function($query) use($term){
            $query->where('code_ofb', 'LIKE', $term . '%');
        })
        ->when(empty($request->type), function($query) use($term){
            $query->where('id', 'LIKE', $term . '%');
        })
        ->when(!empty($groupe), function($query) use($groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs){
            $query->when(!empty($groupe_departements), function($q) use($groupe_departements){
                $q->whereIn('mares.departement_code', $groupe_departements);
            });
            $query->when(!empty($groupe_intercommunalites), function($q) use($groupe_intercommunalites){
                $q->whereIn('mares.intercommunalite_siren', $groupe_intercommunalites);
            });
            $query->when(!empty($groupe_communes), function($q) use($groupe_communes){
                $q->whereIn('mares.commune_insee', $groupe_communes);
            });
            $query->when(!empty($groupe_utilisateurs), function($q) use($groupe_utilisateurs){
                $q->whereIn('mares.utilisateur_id', $groupe_utilisateurs);
            });
            $query->when(empty($groupe_departements) && empty($groupe_intercommunalites) && empty($groupe_communes) && empty($groupe_utilisateurs), function($q){
                $q->where('mares.utilisateur_id', '=', \Auth::id());
            });
        })
        ->select('id', 'nom', 'departement_code', 'intercommunalite_siren', 'commune_insee')
        ->get();

        foreach ($m as $mare) {
            $label = $mare->id.' - '.$mare->nom.' - '.$mare->departement_code;
            $mares[] = [
                'id' => $mare->id,
                'label' => $label
            ];
        }

        return response()->json(compact('mares'));
    }

    /*
     * Récupère toutes les mares du user
     * filtre par statut si indiqué
     */
    private function filterInput($request, $default)
    {
        $statut_id = $request->statut_id != null ? $request->statut_id : null;
        $per_page = $request->per_page != null ? $request->per_page : 10;
        $current_sort = $request->currentSort != null ? $request->currentSort : $default;
        $current_sort_dir = $request->currentSortDir != null ? $request->currentSortDir : 'asc';
        return compact('statut_id', 'per_page', 'current_sort', 'current_sort_dir');
    }


    /*
     * Récupère une mare en fonction de son ID
     */
    public function getMaresByIdentifiant(Request $request)
    {
        $mare_id = $request->mare_id;

        extract($this->filterInput($request, 'id'));

        $mares = Mare::MesMaresData()
                ->where('mares.id', '=', $mare_id)
                ->whereHas('statut', function($q){
                    $q->where('nom_interne', '=', 'valider');
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        return response()->json(compact('mares'));

    }


    /*
     * Récupère les mares en fonction du département
     */
    public function getMaresByDepartement(Request $request)
    {
        $departement_code = $request->departement_code;

        extract($this->filterInput($request, 'id'));

        $mares = Mare::MesMaresData()
                ->where('departement_code', '=', $departement_code)
                ->whereHas('statut', function($q){
                    $q->where('nom_interne', '=', 'valider');
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        return response()->json(compact('mares'));
    }


    public function getMaresByIntercommunalite(Request $request)
    {
        $intercommunalite_siren = $request->intercommunalite_siren;

        extract($this->filterInput($request, 'id'));

        $mares = Mare::MesMaresData()
                ->where('intercommunalite_siren', '=', $intercommunalite_siren)
                ->whereHas('statut', function($q){
                    $q->where('nom_interne', '=', 'valider');
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        return response()->json(compact('mares'));
    }


    public function getMaresByCommune(Request $request)
    {
        $commune_insee = $request->commune_insee;

        extract($this->filterInput($request, 'id'));

        $mares = Mare::MesMaresData()
                ->where('commune_insee', '=', $commune_insee)
                ->whereHas('statut', function($q){
                    $q->where('nom_interne', '=', 'valider');
                })
                ->orderby($current_sort, $current_sort_dir)
                ->paginate($per_page);

        foreach ($mares as $mare) {
            $mare = $this->sanitizeMare($mare);
        }

        return response()->json(compact('mares'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
