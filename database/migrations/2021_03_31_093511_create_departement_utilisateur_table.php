<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartementUtilisateurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departement_utilisateur', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('departement_id')->nullable()->index();
            $table->foreign('departement_id')->references('id')->on('departements');

            $table->integer('utilisateur_id')->nullable()->index();
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');

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
        Schema::dropIfExists('departement_utilisateur');
    }
}
