<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaireGroupeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulaire_groupe', function (Blueprint $table) {
            $table->id();

            $table->integer('groupe_id')->nullable()->index();
            $table->foreign('groupe_id')->references('id')->on('groupes');

            $table->integer('formulaire_id')->nullable()->index();
            $table->foreign('formulaire_id')->references('id')->on('formulaires');
            
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
        Schema::dropIfExists('formulaire_groupe');
    }
}
