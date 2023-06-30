<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandeComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demande_comptes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->string('prenom', 150);
            $table->string('email', 150);
            $table->string('telephone_fixe', 20)->nullable();
            $table->string('telephone_portable', 20)->nullable();
            $table->boolean('valide')->default(0);
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
        Schema::dropIfExists('demande_comptes');
    }
}
