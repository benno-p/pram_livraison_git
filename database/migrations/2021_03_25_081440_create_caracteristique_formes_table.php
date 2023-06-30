<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracteristiqueFormesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracteristique_formes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Ronde / Ovale',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Triangle',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Carré / Rectangle',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Patatoïde',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_formes')->insert(
            [
                'nom' => 'Complexe (en U, digitée)',
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
        Schema::dropIfExists('caracteristique_formes');
    }
}
