<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieTurbiditesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_turbidites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_turbidites')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_turbidites')->insert(
            [
                'nom' => 'Limpide',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_turbidites')->insert(
            [
                'nom' => 'Trouble',
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
        Schema::dropIfExists('hydrologie_turbidites');
    }
}
