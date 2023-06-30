<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\TaxonFaune;
use App\TaxonFlore;
use App\TaxonEeeFaune;
use App\TaxonEeeFlore;
use Validator;


class TaxonController extends Controller
{
    private $db;

    public function __construct()
    {
        $taxons_routes = [
            'tax_faune_groupes' => ['taxon_faunes.index', 'taxon_faunes.create', 'taxon_faunes.store', 'taxon_faunes.show', 'taxon_faunes.edit', 'taxon_faunes.update', 'taxon_faunes.destroy'],
            'tax_flore_groupes' => ['taxon_flores.index', 'taxon_flores.create', 'taxon_flores.store', 'taxon_flores.show', 'taxon_flores.edit', 'taxon_flores.update', 'taxon_flores.destroy'],
            'tax_eee_faune_groupes' => ['taxon_eee_faunes.index', 'taxon_eee_faunes.create', 'taxon_eee_faunes.store', 'taxon_eee_faunes.show', 'taxon_eee_faunes.edit', 'taxon_eee_faunes.update', 'taxon_eee_faunes.destroy'],
            'tax_eee_flore_groupes' => ['taxon_eee_flores.index', 'taxon_eee_flores.create', 'taxon_eee_flores.store', 'taxon_eee_flores.show', 'taxon_eee_flores.edit', 'taxon_eee_flores.update', 'taxon_eee_flores.destroy']
        ];

        foreach ($taxons_routes as $key => $routes) {
            foreach ($routes as $route) {
                if(Route::currentRouteName() == $route){
                    $this->db = $key;
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
        $taxons = DB::table($this->db)->orderby('nom', 'asc')->get();
        return response()->json(compact('taxons'));

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
        $db = $this->db;
        $validator = Validator::make($request->all(),[
            'nom' => "required|string|max:255|unique:$db,nom",
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $taxon = DB::table($db)->insert([
            'nom' => $request->nom,
        ]);


        if($taxon){
            $error = false;
            $message = [['Le taxon a bien été créé']];
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
        $taxon = DB::table($this->db)->find($id);

        if($taxon){
            return response()->json(compact('taxon'));
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
        $validator = Validator::make($request->all(),[
            'nom' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $taxon = DB::table($this->db)->find($id);

        if($taxon){

            $taxon = DB::table($this->db)
              ->where('id', $id)
              ->update(['nom' => $request->nom]);

            $error = false;
            $message = [['Le taxon a bien été modifié']];
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
        $taxon = DB::table($this->db)->find($id);

        if($taxon){

            if($this->db == 'tax_faune_groupes'){
                $taxon = TaxonFaune::find($id);
                $relation = $taxon->faunes();
            }elseif ($this->db == 'tax_flore_groupes') {
                $taxon = TaxonFlore::find($id);
                $relation = $taxon->flores();
            }elseif ($this->db == 'tax_eee_faune_groupes') {
                $taxon = TaxonEeeFaune::find($id);
                $relation = $taxon->eee_faunes();
            }elseif ($this->db == 'tax_eee_flore_groupes') {
                $taxon = TaxonEeeFlore::find($id);
                $relation = $taxon->eee_flores();
            }


            if($relation->exists()){
                return response()->json([
                    'error' => true, 
                    'message' => [['Une relation avec le taxon a été trouvée. Vous ne pouvez pas supprimer ce taxon']]
                ]);
            }else{
                $taxon->delete();
                return response()->json([
                    'error' => false, 
                    'message' => [['Le taxon a bien été supprimé']]
                ]);
            }
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);
    }
}
