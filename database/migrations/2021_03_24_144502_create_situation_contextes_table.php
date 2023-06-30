<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationContextesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situation_contextes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Tourbière acide',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Marais',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Bas-marais / tourbière alcaline',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Marais continental salé ou saumâtre',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Pelouse sèche',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Prairie mésophile',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Prairie humide',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Fourrés, bosquets',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Lande humide',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Lande sèche',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Bois de feuillus',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Bois de résineux',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Culture',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Jardin, parc, cour (de ferme)',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Carrière',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'Annexe routière / ferroviaire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_contextes')->insert(
            [
                'nom' => 'indéterminé',
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
        Schema::dropIfExists('situation_contextes');
    }
}
