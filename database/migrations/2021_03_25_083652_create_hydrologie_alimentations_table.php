<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieAlimentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_alimentations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Aucune',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Ruissellement voirie',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Ruissellement culture',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Source',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Nappe',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Pluvial bâti',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Autre',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_alimentations')->insert(
            [
                'nom' => 'Indeterminée',
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
        Schema::dropIfExists('hydrologie_alimentations');
    }
}
