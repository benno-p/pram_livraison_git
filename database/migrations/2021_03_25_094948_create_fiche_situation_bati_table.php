<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFicheSituationBatiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiche_situation_bati', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('fiche_id')->nullable()->index();
            $table->foreign('fiche_id')->references('id')->on('fiches');

            $table->integer('situation_bati_id')->nullable()->index();
            $table->foreign('situation_bati_id')->references('id')->on('situation_batis');


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
        Schema::dropIfExists('fiche_situation_bati');
    }
}
