<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExcelExport;

use App\Traits\MareTrait;
use App\Utilisateur;
use App\Observateur;
use App\Mare;
use App\TypeMare;
use App\StadeEvolutionMare;
use App\UsageMare;
use App\Fiche;
use App\UsageDechet;
use App\SituationTopographie;
use App\SituationContexte;
use App\SituationBati;
use App\SituationCloture;

use App\CaracteristiqueForme;
use App\CaracteristiqueEauHauteur;
use App\CaracteristiqueFondMare;
use App\CaracteristiqueBerge;
use App\CaracteristiquePietinement;

use App\HydrologieAlimentation;
use App\HydrologieExutoire;
use App\HydrologieRegime;
use App\HydrologieReseau;
use App\HydrologieTampon;
use App\HydrologieTurbidite;

use App\Intervention;

use App\EcologieBoisement;
use App\EcologieOmbrage;

use App\TaxonFaune;

use Validator;
use PDF;

use App\Jobs\ExportExcelJob;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportController extends Controller
{
    use MareTrait;

    public function exportUtilisateurs(Request $request)
    {
        // return var_dump($request->utilisateurs);
        $data = $request->utilisateurs;
        $options = null;
        $view = 'exports_utilisateurs';
        $filename = 'exports_utilisateurs_'.date('ymdHis').'.xlsx';

        // return view('exports.excel.'.$view, compact('data', 'options'));
        \Excel::store(new ExcelExport($view, $data, $options), $filename);
        return response()->json(['statut' => 'success', 'message' => 'Export réussi', 'url' => url("download_excel/$filename")]);



    }


    public function fichePdf($id)
    {
        $fiche = Fiche::find($id);
        $types_mares = TypeMare::select('id', 'nom')->get();
        $stades = StadeEvolutionMare::get();
        $usages_mares = UsageMare::select('id', 'nom')->get();
        $usages_dechets = UsageDechet::select('id', 'nom')->get();
        $topographies =  SituationTopographie::select('id', 'nom')->get();
        $contextes = SituationContexte::select('id', 'nom')->get();
        $batis = SituationBati::select('id', 'nom')->get();
        $clotures = SituationCloture::select('id', 'nom')->get();

        $formes = CaracteristiqueForme::select('id', 'nom')->get();
        $eau_hauteurs = CaracteristiqueEauHauteur::select('id', 'nom')->get();
        $fond_mares = CaracteristiqueFondMare::select('id', 'nom')->get();
        $berges = CaracteristiqueBerge::select('id', 'nom')->get();
        $surpietinements = CaracteristiquePietinement::select('id', 'nom')->get();

        $regimes = HydrologieRegime::select('id', 'nom')->get();
        $reseaux = HydrologieReseau::select('id', 'nom')->get();
        $alimentations = HydrologieAlimentation::select('id', 'nom')->get();
        $turbidites = HydrologieTurbidite::select('id', 'nom')->get();
        $tampons = HydrologieTampon::select('id', 'nom')->get();
        $exutoires = HydrologieExutoire::select('id', 'nom')->get();

        $boisements = EcologieBoisement::select('id', 'nom')->get();
        $ombrages = EcologieOmbrage::select('id', 'nom')->get();

        $interventions = Intervention::select('id', 'nom')->get();

        $groupe_faunistique = [];
        $taxons_faunes = TaxonFaune::pluck('nom', 'id')->toArray();


        $recouvrements = [
            'ecologie_helophytes' => 'vegetation/helophytes.png',
            'ecologie_hydrophytes' => '/vegetation/hydrophytes_enracines.png',
            'ecologie_hydrophytes_non_enracines' => '/vegetation/hydrophytes_non_enracines.png',
            'ecologie_algues' => '/vegetation/algues.png',
            'ecologie_eau_libre' => '/vegetation/eau_libre.png',
            'ecologie_fond_exonde' => '/vegetation/fond_exonde.png'
        ];

        foreach($fiche->faunes as $faune){
            if(array_key_exists($faune->tax_faune_groupe_id, $taxons_faunes)){
                $groupe_faunistique[$faune->tax_faune_groupe_id] = $taxons_faunes[$faune->tax_faune_groupe_id];
            }
        }

        if(empty($groupe_faunistique)){
            $groupe_faunistique[] = 'Non renseigné';
        }

        $fiche->groupe_faunistique = $groupe_faunistique;
        $filename = 'export_fiche_'.$id.'_'.date('ymdHis').".pdf";

        // return 
        PDF::loadView('exports.pdf.fiche_pdf', compact('fiche', 'types_mares', 'stades', 'usages_mares', 'usages_dechets', 'topographies', 'contextes', 'batis', 'clotures', 'formes', 'eau_hauteurs', 'fond_mares', 'berges', 'surpietinements', 'regimes', 'reseaux', 'alimentations', 'turbidites','tampons','exutoires', 'recouvrements', 'boisements', 'ombrages', 'interventions'))
        ->setPaper('a4', 'landscape')
        ->save(public_path("exports/$filename"));
        // ->download('fichier.pdf');
        // ->stream();

        return response()->json(['statut' => 'success', 'message' => 'Export réussi', 'url' => url("download/$filename")]);
        // return var_dump($pdf);
    }

    public function downloadExcel($file)
    {
        return response()->download(storage_path('app').'/'.$file, $file, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend(true);
    }


    /*
     * Télécharge les exports si tout est OK
     */
    public function download($file)
    {
        // return response()->download(storage_path('app').'/'.$file, $file, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend(true);
        return response()->download(public_path('exports').'/'.$file, $file, ['Content-Type' => 'application/pdf'])->deleteFileAfterSend(true);
    }

    public function index()
    {
        $colonnes_mares = [
            'id' => true,
            'x_l93' => true,
            'y_l93' => true,
            'lng' => true,
            'lat' => true,
            'nom' => true,
            'organisme_origine' => true,
            'id_origine' => true,
            'auteur_origine' => true,
            'annee_origine' => true,
            'date_observation_origine' => true,
            'departement_code' => true,
            'intercommunalite_siren' => true,
            'commune_insee' => true,
            'utilisateur' => true,
            'type_observateur' => true,
            // 'type_observateur_autre' => true,
            'type_propriete' => true,
            'situation_topographie' => true,
            // 'situation_topographie_texte' => true,
            'caracterisation' => true,
            'commentaire' => true,
            'commentaire_validation' => true,
            // 'valide' => true,
            'code_ofb' => true,
            'statut' => true,
            'observateur' => true,
            'date_saisie' => true,
            'annee_saisie' => true
        ];

        $colonnes_fiches = [
            'informations_generales' => true,
            'usages' => true,
            'situation' => true,
            'caracteristiques_abiotiques_mare' => true,
            'hydrologie' => true,
            'ecologie' => true,
            'intervention' => true,
            'remarque_generale' => true,
            'date_saisie_fiche' => true,
            'annee_saisie_fiche' => true
        ];

        $types = [
            [
                'nom' => 'Mares',
                'nom_interne' => 'mares',
                'colonnes' => [
                    'mare' => $colonnes_mares
                ]
            ],
            [
                'nom' => 'Mares et fiches',
                'nom_interne' => 'mares_et_fiches',
                'colonnes' => [
                    'mare' => $colonnes_mares,
                    'fiche' => $colonnes_fiches
                ]
            ],
            [
                'nom' => 'Mares et dernière fiche',
                'nom_interne' => 'mares_et_derniere_fiche',
                'colonnes' => [
                    'mare' => $colonnes_mares,
                    'fiche' => $colonnes_fiches
                ]
            ]
        ];

        extract($this->checkGroupeUser());
        $departements = $this->checkDepartementsFromGroupe($groupe, $groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs);

        // return var_dump($departements);

        return response()->json(compact('types', 'departements'));
    }



    public function export(Request $request)
    {
        $type_request = json_decode($request->type);
        $departement_code = $request->departement_code;
        // return var_dump($departement_code);

        if($type_request && $type_request->nom_interne){
            $type = $type_request->nom_interne;

            $utilisateur =  Utilisateur::UtilisateurGroupeId()->find(\Auth::id());
            $observateur = Observateur::where('utilisateur_id', '=', $utilisateur->id)->first();
            extract($this->checkGroupeUser());

            if($utilisateur){
                $view = '';

                if(!empty($type) && $type === 'mares'){
                    $view = 'exports_mares';
                }elseif(!empty($type) && $type === 'mares_et_fiches'){
                    $view = 'exports_mares_fiches';
                }elseif(!empty($type) && $type === 'mares_et_derniere_fiche'){
                    $view = 'exports_mares_derniere_fiche';
                }


                $data = Mare::
                without(['departement', 'intercommunalite', 'commune'])
                ->with([
                    'utilisateur' => function($query){
                        $query->select('utilisateurs.id', 'utilisateurs.nom', 'utilisateurs.prenom');
                    },
                    'type_observateur' => function($query){
                        $query->select('type_observateurs.id', 'type_observateurs.nom');
                    },
                    'type_propriete' => function($query){
                        $query->select('type_proprietes.id', 'type_proprietes.nom');
                    },
                    'situation_topographie' => function($query){
                        $query->select('situation_topographies.id', 'situation_topographies.nom');
                    },
                    'caracterisation' => function($query){
                        $query->select('caracterisations.id', 'caracterisations.nom');
                    },
                    'statut' => function($query){
                        $query->select('statuts.id', 'statuts.nom');
                    },
                    'observateur' => function($query){
                        $query->select('observateurs.id', 'observateurs.nom', 'observateurs.prenom');
                    },
                ])
                ->when($view === 'mares', function($query){
                    $query->without('fiches');
                })
                ->when($view === 'exports_mares_fiches', function($query){
                    $query
                    ->join('fiches as f', 'f.mare_id', '=', 'mares.id')
                    ->with('fiches');
                })
                ->when($view === 'exports_mares_derniere_fiche', function($query){
                    $query->with('latestFiche');
                })
                ->when(!empty($observateur) && \Auth::user()->hasRole('utilisateur'), function($query) use($observateur){
                    $query
                    ->where('mares.utilisateur_id', '=', \Auth::id())
                    ->orWhere('mares.observateur_id', '=', $observateur->id);
                })
                ->when(empty($observateur) && \Auth::user()->hasRole('utilisateur'), function($query) use($observateur){
                    $query
                    ->where('mares.utilisateur_id', '=', \Auth::id());
                })
                ->when(!empty($groupe), function($query) use($groupe_departements, $groupe_intercommunalites, $groupe_communes, $groupe_utilisateurs, $departement_code){
                    $query->when(!empty($groupe_departements), function($q) use($groupe_departements, $departement_code){
                        // $q->orWhereIn('mares.departement_code', $groupe_departements);
                        $q->orwhere('mares.departement_code', $departement_code);
                    });
                    $query->when(!empty($groupe_intercommunalites), function($q) use($groupe_intercommunalites){
                        $q->orWhereIn('mares.intercommunalite_siren', $groupe_intercommunalites);
                    });
                    $query->when(!empty($groupe_communes), function($q) use($groupe_communes){
                        $q->orWhereIn('mares.commune_insee', $groupe_communes);
                    });
                    $query->when(!empty($groupe_utilisateurs), function($q) use($groupe_utilisateurs){
                        $q->orWhereIn('mares.utilisateur_id', $groupe_utilisateurs);
                    });
                })
                ->where('mares.departement_code', $departement_code)
                // ->distinct('mares.id')
                ->select(
                    'mares.id',
                    'mares.x_l93',
                    'mares.y_l93',
                    'mares.lng',
                    'mares.lat',
                    'mares.nom',
                    'mares.organisme_origine',
                    'mares.id_origine',
                    'mares.auteur_origine',
                    'mares.annee_origine',
                    'mares.date_observation_origine',
                    'mares.departement_code',
                    'mares.intercommunalite_siren',
                    'mares.commune_insee',
                    'mares.utilisateur_id',
                    'mares.type_observateur_id',
                    'mares.type_observateur_autre',
                    'mares.type_propriete_id',
                    'mares.situation_topographie_id',
                    'mares.situation_topographie_texte',
                    'mares.caracterisation_id',
                    'mares.commentaire',
                    'mares.commentaire_validation',
                    'mares.code_ofb',
                    'mares.statut_id',
                    'mares.observateur_id',
                    'mares.created_at'
                )
                ->orderby('mares.id', 'asc');


                $nbre_entrees = $data->count();
                $skip = $request->skip;

                // if($nbre_entrees > 5000 && $skip === null){
                //     $nbre_intervals = $nbre_entrees / 5000;

                //     $intervals = [];
                //     $min = 0;
                //     $total_min = 0;
                //     $max = 5000;

                //     for ($i=0; $i <= $nbre_intervals ; $i++) {
                //         if($i < $nbre_intervals - 1){
                //             $intervals[] = ['skip' => $total_min, 'label' => $min. ' - ' .$max];
                //         }else{
                //             $intervals[] = ['skip' => $total_min, 'label' => $min. ' - ' . ($nbre_entrees - $total_min) + $total_min];
                //         }
                //         $total_min += 5000;
                //         $min = $total_min + 1;
                //         $max += 5000;
                //     }

                //     return response()->json([
                //         'statut' => 'error',
                //         'message' => "Votre requête dépasse les 5000 entrées (total : ".$nbre_entrees."), merci de sélectionner un intervalle.",
                //         'intervals' => $intervals,
                //     ]);
                // }else{
                    if($skip === null){
                        $skip = 0;
                    }

                    $mares_colonnes = [];
                    $fiches_colonnes = [];

                    if($type_request->colonnes && property_exists($type_request->colonnes, 'mare')){
                        $mares_colonnes = (Array) $type_request->colonnes->mare;
                    }

                    if($type_request->colonnes && property_exists($type_request->colonnes, 'fiche')){
                        foreach ($type_request->colonnes->fiche as $key => $value) {
                            if($value === true){
                                switch ($key) {
                                    case 'informations_generales':
                                        $fiches_colonnes['id'] = true;
                                        $fiches_colonnes['utilisateur'] = true;
                                        $fiches_colonnes['observateur'] = true;
                                        $fiches_colonnes['date_observation'] = true;
                                        $fiches_colonnes['created_at'] = true;
                                        break;
                                    case 'usages':
                                        $fiches_colonnes['type_mare'] = true;
                                        $fiches_colonnes['stade_evolution_mare'] = true;
                                        $fiches_colonnes['usage_mares'] = true;
                                        $fiches_colonnes['pompe_a_nez'] = true;
                                        $fiches_colonnes['usage_dechets'] = true;
                                        break;
                                    case 'situation':
                                        $fiches_colonnes['situation_contextes'] = true;
                                        $fiches_colonnes['situation_batis'] = true;
                                        $fiches_colonnes['situation_cloture'] = true;
                                        $fiches_colonnes['haie_contact_mare'] = true;
                                        break;
                                    case 'caracteristiques_abiotiques_mare':
                                        $fiches_colonnes['caracteristique_forme'] = true;
                                        $fiches_colonnes['caracteristique_eau_hauteur'] = true;
                                        $fiches_colonnes['caracteristique_longueur'] = true;
                                        $fiches_colonnes['caracteristique_largeur'] = true;
                                        $fiches_colonnes['caracteristique_fond_mare'] = true;
                                        $fiches_colonnes['caracteristique_berge'] = true;
                                        $fiches_colonnes['caracteristique_curage_haut_berge'] = true;
                                        $fiches_colonnes['caracteristique_curage_haut_berge_texte'] = true;
                                        $fiches_colonnes['caracteristique_pietinement'] = true;
                                        break;
                                    case 'hydrologie':
                                        $fiches_colonnes['hydrologie_regime'] = true;
                                        $fiches_colonnes['hydrologie_reseaus'] = true;
                                        $fiches_colonnes['hydrologie_alimentations'] = true;
                                        $fiches_colonnes['hydrologie_turbidite'] = true;
                                        $fiches_colonnes['couleur_specifique'] = true;
                                        $fiches_colonnes['couleur_specifique_autre'] = true;
                                        $fiches_colonnes['hydrologie_tampon'] = true;
                                        $fiches_colonnes['hydrologie_exutoire'] = true;
                                        break;
                                    case 'ecologie':
                                        $fiches_colonnes['ecologie_helophytes'] = true;
                                        $fiches_colonnes['ecologie_hydrophytes'] = true;
                                        $fiches_colonnes['ecologie_hydrophytes_non_enracines'] = true;
                                        $fiches_colonnes['ecologie_algues'] = true;
                                        $fiches_colonnes['ecologie_eau_libre'] = true;
                                        $fiches_colonnes['ecologie_fond_exonde'] = true;
                                        $fiches_colonnes['ecologie_boisement'] = true;
                                        $fiches_colonnes['ecologie_ombrage'] = true;
                                        $fiches_colonnes['faunes'] = true;
                                        $fiches_colonnes['flores'] = true;
                                        $fiches_colonnes['eee_faunes'] = true;
                                        $fiches_colonnes['eee_flores'] = true;
                                        break;
                                    case 'intervention':
                                        $fiches_colonnes['interventions'] = true;
                                        $fiches_colonnes['intervenir_objectif'] = true;
                                        break;
                                    case 'remarque_generale':
                                        $fiches_colonnes['remarque_generale'] = true;
                                        break;
                                    case 'date_saisie_fiche':
                                        $fiches_colonnes['date_saisie_fiche'] = true;
                                        break;
                                    case 'annee_saisie_fiche':
                                        $fiches_colonnes['annee_saisie_fiche'] = true;
                                        break;
                                }
                            } 
                        }
                    }
                // }

                $options['mares_colonnes'] = $mares_colonnes;
                $options['fiches_colonnes'] = $fiches_colonnes;

                $folder_path = 'exports/export_'.$type.'_'.date('ymdHis').'.zip';
                $folder_name = 'export_'.$type.'_'.date('ymdHis').'.zip';


                $zip = new \ZipArchive();
                $zip->open($folder_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $min = 0;
                $max = 1000;

                // ExportExcelJob::dispatch($options, $type, $view, $utilisateur, $observateur);


                // $data = $data
                //         ->skip($skip)
                //         ->take(1000)
                //         ->get();

                // // return var_dump($data);
                // return view('exports.excel.'.$view, compact('data', 'options'));

                // $data = 

                $vue = [];

                $fiche_number = 0;
                $last_mare_id = null;
                $all_files = [];

                $folder_path = public_path('exports/export_'.$type.'_'.date('ymdHis').'.zip');
                $folder_name = 'export_'.$type.'_'.date('ymdHis').'.zip';

                // $zip = new \ZipArchive();
                // $zip->open($folder_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $filename = 'export_'.$type.'_'.date('ymdHis').".xlsx";
                
                (new FastExcel($this->test($data, $min, $max, $options, $vue, $view, $last_mare_id, $fiche_number)))->export(public_path('exports/').$filename);
               
                // $data->chunk(1000, function($mares) use($type, $options, $view, $folder_name, &$min, &$max, $vue, $fiche_number, $last_mare_id, $zip, &$all_files){                        
                //     $filename = $min.'_'.$max.'_'.date('ymdHis').".xlsx";
                //     $min += 1000;
                //     $max += 1000;
                    
                //     $file = (new FastExcel($mares))->export('exports/'.$filename, function ($mare) use($options, $view, $vue, &$fiche_number, &$last_mare_id){
                //         $mare = (Object) $mare;

                //         if($view === 'exports_mares'){
                //             $vue = export_data_mare($vue, $options, $mare);
                //         }
                //         elseif($view === 'exports_mares_fiches'){
                //             $vue = export_data_mare($vue, $options, $mare);

                //             if($last_mare_id === $mare->id){
                //                 $fiche_number++;
                //             }else{
                //                 $fiche_number = 0;
                //             }

                //             $last_mare_id = $mare->id;

                //             $fiche = $mare->fiches->skip($fiche_number)->take(1)->first();
                            
                //             if($fiche){
                //                 $vue = export_data_fiche($vue, $options, $fiche);
                //             }
                //         }elseif($view === 'exports_mares_derniere_fiche'){
                //             $fiche = $mare->latestFiche;
                //             $vue = export_data_mare($vue, $options, $mare);
                //             $vue = export_data_fiche($vue, $options, $fiche);
                //         }


                //         return $vue;
                //     });

                //     $zip->addFile($file, $filename);

                //     $all_files[] = $filename;

                    


                //     // return view('exports.excel.'.$view, compact('data', 'options'));
                //     // \Excel::store(new ExcelExport($view, $mares, $options), $filename);
                //     // (new ExcelExport($view, $mares, $options))->queue('public/exports/' . $filename);
                //     // $zip->addFile(\Excel::download(new ExcelExport($view, $mares, $options), $filename)->getFile(), $filename);
                // });

                // $zip->close();


                // foreach ($all_files as $value) {
                //     if(file_exists(public_path('exports/'.$value))){
                //         unlink(public_path('exports/'.$value));   
                //     }
                // }

                

                // return var_dump($data);


                // return response()->json(['statut' => 'success', 'data' => $data]);


                // $filename = 'export_'.$type.'_'.date('ymdHis').".xlsx";
                // $options['mares_colonnes'] = $mares_colonnes;
                // $options['fiches_colonnes'] = $fiches_colonnes;

                // return view('exports.excel.'.$view, compact('data', 'options'));

                // \Excel::store(new ExcelExport($view, $data, $options), $filename);

                // $test = (new ExcelExport($view, $data, $options))->queue($filename);

                // return back()->withSuccess('Export started!');

                return response()->json(['statut' => 'success', 'message' => 'Export réussi', 'url' => url("download/$filename")]);

            }
        }
    }


    public function test($data, $min, $max, $options, $vue, $view, $last_mare_id, $fiche_number)
    {

        foreach ($data->cursor() as $mare) {
            if($view === 'exports_mares'){
                $vue = export_data_mare($vue, $options, $mare);
            }
            elseif($view === 'exports_mares_fiches'){
                $vue = export_data_mare($vue, $options, $mare);

                if($last_mare_id === $mare->id){
                    $fiche_number++;
                }else{
                    $fiche_number = 0;
                }

                $last_mare_id = $mare->id;

                $fiche = $mare->fiches->skip($fiche_number)->take(1)->first();
                
                if($fiche){
                    $vue = export_data_fiche($vue, $options, $fiche);
                }
            }elseif($view === 'exports_mares_derniere_fiche'){
                $fiche = $mare->latestFiche;
                $vue = export_data_mare($vue, $options, $mare);
                $vue = export_data_fiche($vue, $options, $fiche);
            }

            yield $vue;
            // yield export_data_mare($vue, $options, $mare);
        }

        // $data->chunk(1000, function($mares) use($type, $options, $view, $folder_name, &$min, &$max, $vue, $fiche_number, $last_mare_id, $zip, &$all_files){                        
        //     $filename = $min.'_'.$max.'_'.date('ymdHis').".xlsx";
        //     $min += 1000;
        //     $max += 1000;
            
        //     // $file = (new FastExcel($mares))->export('exports/'.$filename, function ($mare) use($options, $view, $vue, &$fiche_number, &$last_mare_id){
        //     //     $mare = (Object) $mare;

        //     //     if($view === 'exports_mares'){
        //     //         $vue = export_data_mare($vue, $options, $mare);
        //     //     }
        //     //     elseif($view === 'exports_mares_fiches'){
        //     //         $vue = export_data_mare($vue, $options, $mare);

        //     //         if($last_mare_id === $mare->id){
        //     //             $fiche_number++;
        //     //         }else{
        //     //             $fiche_number = 0;
        //     //         }

        //     //         $last_mare_id = $mare->id;

        //     //         $fiche = $mare->fiches->skip($fiche_number)->take(1)->first();
                    
        //     //         if($fiche){
        //     //             $vue = export_data_fiche($vue, $options, $fiche);
        //     //         }
        //     //     }elseif($view === 'exports_mares_derniere_fiche'){
        //     //         $fiche = $mare->latestFiche;
        //     //         $vue = export_data_mare($vue, $options, $mare);
        //     //         $vue = export_data_fiche($vue, $options, $fiche);
        //     //     }


        //     //     return $vue;
        //     // });

        //     // $zip->addFile($file, $filename);

        //     // $all_files[] = $filename;

            


        //     // return view('exports.excel.'.$view, compact('data', 'options'));
        //     // \Excel::store(new ExcelExport($view, $mares, $options), $filename);
        //     // (new ExcelExport($view, $mares, $options))->queue('public/exports/' . $filename);
        //     // $zip->addFile(\Excel::download(new ExcelExport($view, $mares, $options), $filename)->getFile(), $filename);
        // });
    }
}