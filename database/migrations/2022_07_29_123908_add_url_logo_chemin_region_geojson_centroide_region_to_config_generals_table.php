<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlLogoCheminRegionGeojsonCentroideRegionToConfigGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config_generals', function (Blueprint $table) {
            $table->string('redirect_url_logo', 255)->nullable();
            $table->string('path_region_geojson', 255)->nullable();

            $table->float('centroide_x_l93', 8, 2)->nullable();
            $table->float('centroide_y_l93', 8, 2)->nullable();

            $table->double('centroide_lng', 8, 2)->nullable();
            $table->double('centroide_lat', 8, 2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_generals', function (Blueprint $table) {
            $table->dropColumn('redirect_url_logo');
            $table->dropColumn('path_region_geojson');
            $table->dropColumn('centroide_x_l93');
            $table->dropColumn('centroide_y_l93');
            $table->dropColumn('centroide_lng');
            $table->dropColumn('centroide_lat');
        });
    }
}
