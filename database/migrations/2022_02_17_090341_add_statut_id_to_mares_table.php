<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutIdToMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mares', function (Blueprint $table) {
            $table->integer('statut_id')->nullable()->index();
            $table->foreign('statut_id')->references('id')->on('statuts');
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
            $table->dropColumn('statut_id');
        });
    }
}
