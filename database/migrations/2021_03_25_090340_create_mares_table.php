<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mares', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->point('geom', 'GEOMETRY', 2154);

            $table->float('x_l93', 8, 2);
            $table->float('y_l93', 8, 2);

            $table->double('lng', 8, 2);
            $table->double('lat', 8, 2);

            $table->string('nom', 100)->nullable();

            // Origine
            $table->string('organisme_origine', 150)->nullable();
            $table->string('id_origine', 50)->nullable();
            $table->string('auteur_origine', 100)->nullable();
            $table->integer('annee_origine')->nullable();
            $table->string('date_observation_origine', 20)->nullable();


            $table->string('departement_code', 5);
            $table->integer('intercommunalite_siren');
            $table->string('commune_insee', 5);


            $table->integer('utilisateur_id')->nullable()->index();
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');

            $table->integer('type_observateur_id')->index();
            $table->foreign('type_observateur_id')->references('id')->on('type_observateurs');
            $table->string('type_observateur_autre', 255)->nullable();

            $table->integer('type_propriete_id')->index();
            $table->foreign('type_propriete_id')->references('id')->on('type_proprietes');

            $table->integer('caracterisation_id')->nullable()->index();
            $table->foreign('caracterisation_id')->references('id')->on('caracterisations');

            $table->integer('situation_topographie_id')->index();
            $table->foreign('situation_topographie_id')->references('id')->on('situation_topographies');
            $table->string('situation_topographie_texte', 255)->nullable();

            $table->text('commentaire')->nullable();
            $table->string('commentaire_validation', 255)->nullable();
            $table->boolean('valide')->default(false);


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
        Schema::dropIfExists('mares');
    }
}
