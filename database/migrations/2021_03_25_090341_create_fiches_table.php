<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiches', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('utilisateur_id')->nullable()->index();
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');

            $table->integer('mare_id')->nullable()->index();
            $table->foreign('mare_id')->references('id')->on('mares');

            // USAGES
            $table->integer('type_mare_id')->nullable()->index();
            $table->foreign('type_mare_id')->references('id')->on('type_mares');

            $table->integer('stade_evolution_mare_id')->nullable()->index();
            $table->foreign('stade_evolution_mare_id')->references('id')->on('stade_evolution_mares');

            $table->boolean('pompe_a_nez')->default(0);

            // SITUATION
            $table->integer('situation_cloture_id')->nullable()->index();
            $table->foreign('situation_cloture_id')->references('id')->on('situation_clotures');

            $table->string('patrimoine_bati_autre', 255)->nullable();

            $table->boolean('haie_contact_mare')->default(0);


            // CARACTERISTIQUE
            $table->integer('caracteristique_forme_id')->nullable()->index();
            $table->foreign('caracteristique_forme_id')->references('id')->on('caracteristique_formes');

            $table->float('caracteristique_longueur', 8, 2)->nullable();
            $table->float('caracteristique_largeur', 8, 2)->nullable();

            $table->integer('caracteristique_eau_hauteur_id')->nullable()->index();
            $table->foreign('caracteristique_eau_hauteur_id')->references('id')->on('caracteristique_eau_hauteurs');

            $table->integer('caracteristique_fond_mare_id')->nullable()->index();
            $table->foreign('caracteristique_fond_mare_id')->references('id')->on('caracteristique_fond_mares');

            $table->integer('caracteristique_berge_id')->nullable()->index();
            $table->foreign('caracteristique_berge_id')->references('id')->on('caracteristique_berges');

            $table->boolean('caracteristique_curage_haut_berge')->default(0);
            $table->string('caracteristique_curage_haut_berge_texte')->nullable();

            $table->integer('caracteristique_pietinement_id')->nullable()->index();
            $table->foreign('caracteristique_pietinement_id')->references('id')->on('caracteristique_pietinements');

            //Hydrologie
            $table->integer('hydrologie_regime_id')->nullable()->index();
            $table->foreign('hydrologie_regime_id')->references('id')->on('hydrologie_regimes');

            $table->integer('hydrologie_turbidite_id')->nullable()->index();
            $table->foreign('hydrologie_turbidite_id')->references('id')->on('hydrologie_turbidites');

            $table->boolean('couleur_specifique')->default(0);
            $table->string('couleur_specifique_autre', 255)->nullable();

            $table->integer('hydrologie_tampon_id')->nullable()->index();
            $table->foreign('hydrologie_tampon_id')->references('id')->on('hydrologie_tampons');

            $table->integer('hydrologie_exutoire_id')->nullable()->index();
            $table->foreign('hydrologie_exutoire_id')->references('id')->on('hydrologie_exutoires');

            //Ecologie
            $table->float('ecologie_helophytes', 8, 2)->nullable();
            $table->float('ecologie_hydrophytes', 8, 2)->nullable();
            $table->float('ecologie_hydrophytes_non_enracines', 8, 2)->nullable();
            $table->float('ecologie_algues', 8, 2)->nullable();
            $table->float('ecologie_eau_libre', 8, 2)->nullable();
            $table->float('ecologie_fond_exonde', 8, 2)->nullable();

            $table->integer('ecologie_boisement_id')->nullable()->index();
            $table->foreign('ecologie_boisement_id')->references('id')->on('ecologie_boisements');

            $table->integer('ecologie_ombrage_id')->nullable()->index();
            $table->foreign('ecologie_ombrage_id')->references('id')->on('ecologie_ombrages');

            //Intervenir
            $table->string('intervenir_objectif', 255)->nullable();

            $table->boolean('valide')->default(0);
            $table->string('commentaire_validation', 255)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fiches');
    }
}
