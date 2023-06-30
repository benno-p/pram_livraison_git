<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsageDechetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usage_dechets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Aucun',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Déchets verts (taille de haie, tonte...)',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Ordure ménagères',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Déchets recyclables (verre, plastique, métal)',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Déchets dangereux (solvant, huile, batterie...)',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Déchets inertes (gravats)',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Meubles',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('usage_dechets')->insert(
            [
                'nom' => 'Électroménager',
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
        Schema::dropIfExists('usage_dechets');
    }
}
