<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracteristiquePietinementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracteristique_pietinements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('caracteristique_pietinements')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_pietinements')->insert(
            [
                'nom' => 'Intense et total',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_pietinements')->insert(
            [
                'nom' => 'Localisé',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_pietinements')->insert(
            [
                'nom' => 'Faible à nul',
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
        Schema::dropIfExists('caracteristique_pietinements');
    }
}
