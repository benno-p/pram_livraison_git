<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\TaxonFaune;
use App\TaxonFlore;
use App\TaxonEee;
use App\Faune;
use App\Flore;
use App\EeeFaune;
use App\EeeFlore;
use Validator;



class EspeceController extends Controller
{
    private $db;
    private $taxon;
    private $rel;

    public function __construct()
    {
        $especes_routes = [
            'tax_faune_especes' => ['faunes.index', 'faunes.create', 'faunes.store', 'faunes.show', 'faunes.edit', 'faunes.update', 'faunes.destroy'],
            'tax_flore_especes' => ['flores.index', 'flores.create', 'flores.store', 'flores.show', 'flores.edit', 'flores.update', 'flores.destroy'],
            'tax_eee_faune_especes' => ['eee_faunes.index', 'eee_faunes.create', 'eee_faunes.store', 'eee_faunes.show', 'eee_faunes.edit', 'eee_faunes.update', 'eee_faunes.destroy'],
            'tax_eee_flore_especes' => ['eee_flores.index', 'eee_flores.create', 'eee_flores.store', 'eee_flores.show', 'eee_flores.edit', 'eee_flores.update', 'eee_flores.destroy'],
        ];

        foreach ($especes_routes as $key => $routes) {
            foreach ($routes as $route) {
                if(Route::currentRouteName() == $route){
                    $this->db = $key;

                    if($key == 'tax_faune_especes'){
                        $this->taxon = 'tax_faune_groupes';
                        $this->rel = 'tax_faune_groupe_id';
                    }elseif($key == 'tax_flore_especes'){
                        $this->taxon = 'tax_flore_groupes';
                        $this->rel = 'tax_flore_groupe_id';
                    }elseif($key == 'tax_eee_faune_especes'){
                        $this->taxon = 'tax_eee_faune_groupes';
                        $this->rel = 'tax_eee_faune_groupe_id';
                    }elseif($key == 'tax_eee_flore_especes'){
                        $this->taxon = 'tax_eee_flore_groupes';
                        $this->rel = 'tax_eee_flore_groupe_id';
                    }

                    break;
                }
            }          
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $especes = DB::table($this->db.' as table')
                    ->join($this->taxon.' as t', 't.id', '=', 'table.'.$this->rel)
                    ->select('table.*', 't.nom as taxon')
                    ->orderby('table.nom', 'asc')
                    ->get();
        return response()->json(compact('especes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taxons = DB::table($this->taxon)->orderby('nom', 'asc')->get();
        return response()->json(compact('taxons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $db = $this->db;
        $db_taxon = $this->taxon;
        $validator = Validator::make($request->all(),[
            'nom' => "required|string|max:255|unique:$db,nom",
            'cdnom' => "required|numeric",
            'taxon_id' => "required|numeric|exists:$db_taxon,id"
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $espece = DB::table($db)->insert([
            'nom' => $request->nom,
            'cdnom' => $request->cdnom,
            $this->rel => $request->taxon_id,
        ]);


        if($espece){
            $error = false;
            $message = [['L\'espèce a bien été créée']];
            return response()->json(compact('error', 'message'));
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
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
        $espece = DB::table($this->db)->find($id);

        if($espece){
            $taxons = DB::table($this->taxon)->orderby('nom', 'asc')->get();
            return response()->json(compact('espece', 'taxons'));
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
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
        $db = $this->db;
        $db_taxon = $this->taxon;
        
        $validator = Validator::make($request->all(),[
            'nom' => 'required|string|max:255',
            'cdnom' => "required|numeric",
            'taxon_id' => "required|numeric|exists:$db_taxon,id"
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $espece = DB::table($this->db)->find($id);

        if($espece){
            $espece = DB::table($this->db)
              ->where('id', $id)
              ->update([
                'nom' => $request->nom,
                'cdnom' => $request->cdnom,
                $this->rel => $request->taxon_id,
            ]);

            $error = false;
            $message = [['L\'espèce a bien été modifiée']];
            return response()->json(compact('error', 'message'));
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $espece = DB::table($this->db)->find($id);

        if($espece){
            if($this->db == 'tax_faune_especes'){
                $espece = Faune::find($id);
            }elseif ($this->db == 'tax_flore_especes') {
                $espece = Flore::find($id);
            }elseif ($this->db == 'tax_eee_faune_especes') {
                $espece = EeeFaune::find($id);
            }elseif ($this->db == 'tax_eee_flore_especes') {
                $espece = EeeFlore::find($id);
            }


            if($espece->fiches()->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Une relation entre l\'espèce et une fiche a été trouvée. Vous ne pouvez pas supprimer cette espèce']]
                ]);
            }else{
                $espece->delete();
                return response()->json([
                    'error' => false, 
                    'message' => [['L\'espèce a bien été supprimée']]
                ]);
            }
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }
}
