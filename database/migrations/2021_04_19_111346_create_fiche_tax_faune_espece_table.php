<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFicheTaxFauneEspeceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiche_tax_faune_espece', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('tax_faune_espece_id')->nullable()->index();
            $table->foreign('tax_faune_espece_id')->references('id')->on('tax_faune_especes');

            $table->integer('fiche_id')->nullable()->index();
            $table->foreign('fiche_id')->references('id')->on('fiches');

            $table->float('nombre_observe', 8, 2)->nullable();
            $table->float('pourcentage', 8, 2)->nullable();

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
        Schema::dropIfExists('fiche_tax_faune_espece');
    }
}
