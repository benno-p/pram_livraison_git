<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observateurs', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 255);
            $table->string('prenom', 255);
            $table->string('organisme', 255)->nullable();
            $table->string('nom_source', 255)->nullable();

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
        Schema::dropIfExists('observateurs');
    }
}
