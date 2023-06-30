<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_generals', function (Blueprint $table) {
            $table->id();

            $table->text('titre_image_accueil')->nullable();
            $table->text('titre_accueil')->nullable();
            $table->text('texte_accueil')->nullable();
            $table->string('image_page_accueil', 255)->nullable();
            $table->string('logo', 255)->nullable();

            $table->timestamps();
        });

        DB::table('config_generals')->insert(
            [
                'titre_image_accueil' => '<h1>Application cartographique</h1><h1>du PRAM Grand Est</h1>',
                'titre_accueil' => "<h2>Bienvenue sur l'outil cartographique du Programme régional d'actions en faveur des mares du Grand Est</h2>",
                'texte_accueil' => "<p><span style=\"background-color: rgb(248, 250, 252);\">Cet outil de consultation et de saisie en ligne des mares en Grand Est a été conçu par le&nbsp;</span><strong style=\"background-color: rgb(248, 250, 252);\">Conservatoire d’espaces naturels de Lorraine</strong><span style=\"background-color: rgb(248, 250, 252);\">, dans le cadre de la mise en œuvre du Programme régional d’actions en faveur des mares du Grand Est (PRAM Grand Est).</span></p><p><span style=\"background-color: rgb(248, 250, 252);\">Il s’adresse à tout type de public (particuliers, professionnels, collectivités…) et permet de recenser des mares de la région Grand Est, de décrire leurs principales caractéristiques, de saisir les espèces de la faune et de la flore qui y vivent et de les consulter.</span></p><p><span style=\"background-color: rgb(248, 250, 252);\">Bonne navigation !</span></p>",
                'image_page_accueil' => '',
                'logo' => '',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_generals');
    }
}
