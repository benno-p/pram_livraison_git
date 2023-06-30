<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganismeAndMotivationToDemandeComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demande_comptes', function (Blueprint $table) {
            $table->string('organisme', 255)->nullable();
            $table->string('motivation', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demande_comptes', function (Blueprint $table) {
            $table->dropColumn('organisme');
            $table->dropColumn('motivation');
        });
    }
}
