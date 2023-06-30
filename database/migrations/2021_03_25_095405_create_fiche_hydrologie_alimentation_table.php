<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFicheHydrologieAlimentationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiche_hydrologie_alimentation', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('fiche_id')->nullable()->index();
            $table->foreign('fiche_id')->references('id')->on('fiches');

            $table->integer('hydrologie_alimentation_id')->nullable()->index();
            $table->foreign('hydrologie_alimentation_id')->references('id')->on('hydrologie_alimentations');

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
        Schema::dropIfExists('fiche_hydrologie_alimentation');
    }
}
