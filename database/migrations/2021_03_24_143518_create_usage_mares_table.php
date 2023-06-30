<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsageMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_mares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Abreuvoir aménagé',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Abreuvoir non aménagé',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Collecte ruissellement',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Pêche',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Chasse',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Réserve incendie',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Ornemental',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Protection de la biodiversité',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Patrimoine culturel / paysager',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Pédagogique',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Abandonné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Lagunage',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_mares')->insert(
            [
                'nom' => 'Inconnu',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );
        
        DB::table('usage_mares')->insert(
            [
                'nom' => 'Mesure compensatoire',
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
        Schema::dropIfExists('usage_mares');
    }
}
