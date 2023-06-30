<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupeDepartementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupe_departement', function (Blueprint $table) {
            $table->id();

            $table->integer('groupe_id')->nullable()->index();
            $table->foreign('groupe_id')->references('id')->on('groupes');

            $table->integer('departement_id')->nullable()->index();
            $table->foreign('departement_id')->references('id')->on('departements');

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
        Schema::dropIfExists('groupe_departement');
    }
}
