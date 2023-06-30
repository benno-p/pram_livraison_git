<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConfigGeneral;
use App\ConfigLogoPartenaire;
use Illuminate\Support\Facades\Cache;
use Validator;

class ConfigurationController extends Controller
{
    public function configurationGenerale()
    {
        $configuration = ConfigGeneral::first();
        return response()->json(compact('configuration'));
    }

    public function updateConfigurationGenerale(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'titre_image_accueil' => 'required|string',
            'titre_accueil' => 'required|string',
            'texte_accueil' => 'required|string',
            'image_page_accueil' => 'nullable|mimes:jpg,JPG,png,PNG,jpeg,JPEG|max:5000',
            'modifier_image_page_accueil' => 'nullable|numeric|min:0|max:1',
            'logo' => 'nullable|mimes:jpg,JPG,png,PNG,jpeg,JPEG|max:5000',
            'modifier_logo' => 'nullable|numeric|min:0|max:1',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'region_geojson' => 'nullable|mimetypes:application/json',
            'modifier_region_geojson' => 'nullable|numeric|min:0|max:1',
            'redirect_logo_url' => 'nullable|string|max:255'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => true, 
                'message' => $validator->messages()
            ]);
        }

        $modifier_image_page_accueil = $request->modifier_image_page_accueil === '1' ? true : false;
        $modifier_logo = $request->modifier_logo === '1' ? true : false;
        $modifier_region_geojson = $request->modifier_region_geojson === '1' ? true : false;

        $data = [
            'titre_image_accueil' => $request->titre_image_accueil,
            'titre_accueil' => $request->titre_accueil,
            'texte_accueil' => $request->texte_accueil,
            'centroide_lng' => $request->lng,
            'centroide_lat' => $request->lat,
            'redirect_url_logo' => $request->redirect_url_logo
        ];

        $configuration = ConfigGeneral::first();

        if(!$configuration){
            $configuration = ConfigGeneral::create($data);
        }else{
            $configuration->update($data);
        }

        $request->image = $request->image_page_accueil;
        updateFileUpload($modifier_image_page_accueil, $request, 'image_page_accueil', $configuration->image_page_accueil, $configuration, 'image_page_accueil');

        $request->image = $request->logo;
        updateFileUpload($modifier_logo, $request, 'logo', $configuration->logo, $configuration, 'logo');

        $request->image = $request->region_geojson;
        updateFileUpload($modifier_region_geojson, $request, 'region_geojson', $configuration->path_region_geojson, $configuration, 'path_region_geojson');

        if(Cache::get('cache_config_generale')){
            Cache::forget('cache_config_generale');
            $seconds = 14400; // 4heures de cache
            $partenaires = ConfigLogoPartenaire::all();
            $configuration->partenaires = $partenaires;
            Cache::put('cache_config_generale', $configuration, $seconds);
        }

        return response()->json([
            'error' => false,
            'message' => ["La configuration a été mise à jour"]
        ], 200);
    }
}
