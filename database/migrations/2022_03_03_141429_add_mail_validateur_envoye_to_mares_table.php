<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailValidateurEnvoyeToMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mares', function (Blueprint $table) {
            $table->boolean('mail_validateur_envoye')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mares', function (Blueprint $table) {
            $table->dropColumn('mail_validateur_envoye');
        });
    }
}
