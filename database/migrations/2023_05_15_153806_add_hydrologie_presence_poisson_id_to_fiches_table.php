<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHydrologiePresencePoissonIdToFichesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiches', function (Blueprint $table) {
            $table->integer('hydrologie_presence_poisson_id')->nullable()->index();
            $table->foreign('hydrologie_presence_poisson_id')->references('id')->on('hydrologie_presence_poissons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiches', function (Blueprint $table) {
            $table->dropColumn('hydrologie_presence_poisson_id');
        });
    }
}
