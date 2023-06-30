<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('tickets')->create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('utilisateur_nom', 150)->nullable();
            $table->string('utilisateur_prenom', 150)->nullable();
            $table->string('utilisateur_email', 150)->nullable();
            $table->string('application', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('objet', 255)->nullable();
            $table->text('description')->nullable();
            $table->boolean('traite')->default(false);
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
        Schema::dropIfExists('tickets');
    }
}
