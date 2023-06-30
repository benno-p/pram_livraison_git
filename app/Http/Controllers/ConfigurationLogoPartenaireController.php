<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConfigLogoPartenaire;
use App\ConfigGeneral;
use Illuminate\Support\Facades\Cache;
use Validator;

class ConfigurationLogoPartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partenaires = ConfigLogoPartenaire::orderby('nom', 'asc')->get();
        return response()->json(compact('partenaires'));
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
        $validator = Validator::make($request->all(),[
            'nom' => 'required|string|max:255',
            'site_url' => 'nullable|string|max:255',
            'logo' => 'nullable|mimes:jpg,JPG,png,PNG,jpeg,JPEG|max:5000',
            'modifier_logo' => 'nullable|numeric|min:0|max:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $modifier_logo = $request->modifier_logo === '1' ? true : false;

        $partenaire = ConfigLogoPartenaire::create([
            'nom' => $request->nom,
            'site_url' => $request->site_url
        ]);

        if($partenaire){
            $request->image = $request->logo;
            updateFileUpload($modifier_logo, $request, 'logo_partenaires', $partenaire->logo, $partenaire, 'logo');

            $configuration = ConfigGeneral::first();

            if(Cache::get('cache_config_generale') && $configuration){
                Cache::forget('cache_config_generale');
                $seconds = 14400; // 4heures de cache
                $partenaires = ConfigLogoPartenaire::all();
                $configuration->partenaires = $partenaires;
                Cache::put('cache_config_generale', $configuration, $seconds);
            }

            return response()->json([
                'error' => false, 
                'message' => [['Le partenaire a bien été enregistré']]
            ]);
        }

        return response()->json([
            'error' => true, 
            'message' => [['Une erreur est survenue']]
        ]);

        

        // if(Cache::get('cache_config_generale')){
        //     Cache::forget('cache_config_generale');
        //     $seconds = 14400; // 4heures de cache
        //     Cache::put('cache_config_generale', $configuration, $seconds);
        // }
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
        $partenaire = ConfigLogoPartenaire::find($id);

        if($partenaire){
            return response()->json(compact('partenaire'));
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
            'site_url' => 'nullable|string|max:255',
            'logo' => 'nullable|mimes:jpg,JPG,png,PNG,jpeg,JPEG|max:5000',
            'modifier_logo' => 'nullable|numeric|min:0|max:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $modifier_logo = $request->modifier_logo === '1' ? true : false;

        $partenaire = ConfigLogoPartenaire::find($id);

        if($partenaire){
            $partenaire->update([
                'nom' => $request->nom,
                'site_url' => $request->site_url
            ]);

            $request->image = $request->logo;
            updateFileUpload($modifier_logo, $request, 'logo_partenaires', $partenaire->logo, $partenaire, 'logo');

            $configuration = ConfigGeneral::first();

            if(Cache::get('cache_config_generale') && $configuration){
                Cache::forget('cache_config_generale');
                $seconds = 14400; // 4heures de cache
                $partenaires = ConfigLogoPartenaire::all();
                $configuration->partenaires = $partenaires;
                Cache::put('cache_config_generale', $configuration, $seconds);
            }

            return response()->json([
                'error' => false, 
                'message' => [['Le partenaire a bien été modifié']]
            ]);
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
        //
    }
}
