<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracteristiqueEauHauteursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracteristique_eau_hauteurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => '0 - 30 cm',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => '30 - 60 cm',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => '60 - 100 cm',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

       DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => '> 100 cm',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ); 

       DB::table('caracteristique_eau_hauteurs')->insert(
            [
                'nom' => 'À sec',
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
        Schema::dropIfExists('caracteristique_eau_hauteurs');
    }
}
