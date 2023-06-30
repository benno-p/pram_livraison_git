<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxEeeFloreEspecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_eee_flore_especes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nom', 255);
            $table->integer('cdnom');

            $table->integer('tax_eee_flore_groupe_id')->nullable()->index();
            $table->foreign('tax_eee_flore_groupe_id')->references('id')->on('tax_eee_flore_groupes');


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
        Schema::dropIfExists('tax_eee_flore_especes');
    }
}
