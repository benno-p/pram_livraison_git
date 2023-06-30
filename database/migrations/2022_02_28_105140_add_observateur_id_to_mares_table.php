<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservateurIdToMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mares', function (Blueprint $table) {
            $table->integer('observateur_id')->nullable()->index();
            $table->foreign('observateur_id')->references('id')->on('observateurs');
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
            $table->dropColumn('observateur_id');
        });
    }
}
