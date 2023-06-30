<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieRegimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_regimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_regimes')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_regimes')->insert(
            [
                'nom' => 'Mare permanente',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_regimes')->insert(
            [
                'nom' => 'Mare temporaire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_regimes')->insert(
            [
                'nom' => 'Indéterminé',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hydrologie_regimes');
    }
}
