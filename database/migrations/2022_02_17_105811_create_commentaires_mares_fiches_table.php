<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentairesMaresFichesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentaires_mares_fiches', function (Blueprint $table) {
            $table->id();

            $table->integer('mare_id')->nullable()->index();
            $table->foreign('mare_id')->references('id')->on('mares');

            $table->integer('fiche_id')->nullable()->index();
            $table->foreign('fiche_id')->references('id')->on('fiches');

            $table->integer('utilisateur_id')->nullable()->index();
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs');

            $table->integer('statut_id')->nullable()->index();
            $table->foreign('statut_id')->references('id')->on('statuts');

            $table->text('message')->nullable();

            $table->boolean('utilisateur_vu')->default(false);
            $table->boolean('validateur_vu')->default(false);

            $table->boolean('utilisateur_send')->default(false);
            $table->boolean('validateur_send')->default(false);


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
        Schema::dropIfExists('commentaires_mares_fiches');
    }
}
