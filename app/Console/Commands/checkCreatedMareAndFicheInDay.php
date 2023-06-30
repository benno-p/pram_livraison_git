<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Utilisateur;
use App\Mare;
use App\Fiche;

use App\Mail\MaresFichesCreatedInDay;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class checkCreatedMareAndFicheInDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_created_mare_and_fiche_in_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regarde les mares et les fiches qui ont été créée dans la journée, pour envoyer un mail aux gestionnaires/admin en fonction du departement de la mare/fiche. Passe la valeur de mail_validateur_envoye dans la table mares et la table fiches à true une fois le mail envoyé';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('***************************************');
        $this->line('***                                 ***');
        $this->line('***                                 ***');
        $this->line('*** CHECK MARE/FICHE CREATE IN DAY  ***');
        $this->line('***                                 ***');
        $this->line('***                                 ***');
        $this->line('***************************************');
        $this->line('Cron démarré '.date('Y-m-d H:i:s'));
        $this->line('***************************************');

        $gestionnaires = Utilisateur::whereHas('role', function($query){
                            $query
                            ->where('nom_interne', '=', 'gestionnaire')
                            ->orwhere('nom_interne', '=', 'administrateur');
                        })
                        ->get();

        $today = Carbon::now()->format('Y-m-d');

        foreach ($gestionnaires as $gestionnaire) {
            if($gestionnaire->departements()->exists()){
                $user_departements = $gestionnaire->departements->pluck('code_insee')->toArray();

                $query_mares = Mare::whereDate('created_at', Carbon::today())
                                ->whereHas('statut', function($q){
                                    $q->where('nom_interne', '=', 'en_attente_de_validation');
                                })
                                ->whereIn('departement_code', $user_departements);

                $query_fiches = Fiche::whereDate('created_at', Carbon::today())
                                ->whereHas('mare', function($query) use($user_departements){
                                    $query->whereHas('statut', function($q){
                                        $q->where('nom_interne', '=', 'valider');
                                    })
                                    ->whereIn('departement_code', $user_departements);
                                })
                                ->whereHas('statut', function($q){
                                    $q->where('nom_interne', '=', 'en_attente_de_validation');
                                });
                                

                $mares = $query_mares->get();
                $fiches = $query_fiches->get();

                if(($mares->count() > 0 || $fiches->count() > 0) && $gestionnaire->email){
                    $this->line('Gestionnaire : '.$gestionnaire->email.' | mares : '.$mares->count().' | fiches : '.$fiches->count().' - ');
                    $mares_count = $mares->count();
                    $fiches_count = $fiches->count();
                    $mares_fiches_sans_validateur = false;
                    Mail::to($gestionnaire->email)->send(new MaresFichesCreatedInDay($mares, $fiches, $mares_fiches_sans_validateur));

                    $query_mares->update(['mail_validateur_envoye' => true]);
                    $query_fiches->update(['mail_validateur_envoye' => true]);

                }
            }
        }

        $administrateurs_emails = 
        Utilisateur::join('roles as r', 'r.id', '=', 'utilisateurs.role_id')
        ->where('r.nom_interne', '=', 'administrateur')
        ->pluck('utilisateurs.email')
        ->toArray();

        $query_mares = Mare::where('mail_validateur_envoye', '=', false)
                        ->whereHas('statut', function($q){
                            $q->where('nom_interne', '=', 'en_attente_de_validation');
                        });

        $query_fiches = Fiche::where('mail_validateur_envoye', '=', false)
                    ->whereHas('mare', function($query) use($user_departements){
                        $query->whereHas('statut', function($q){
                            $q->where('nom_interne', '=', 'valider');
                        });
                    })
                    ->whereHas('statut', function($q){
                        $q->where('nom_interne', '=', 'en_attente_de_validation');
                    });

        $mares = $query_mares->get();
        $fiches = $query_fiches->get();

        if(($mares->count() > 0 || $fiches->count() > 0) && !empty($administrateurs_emails)){
            $mares_count = $mares->count();
            $fiches_count = $fiches->count();
            $mares_fiches_sans_validateur = true;
            Mail::to($administrateurs_emails)->send(new MaresFichesCreatedInDay($mares, $fiches, $mares_fiches_sans_validateur));
        }

        $this->line('Cron terminé '.date('Y-m-d H:i:s'));
        $this->line('***************************************');
    }
}
