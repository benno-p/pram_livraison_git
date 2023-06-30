<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieReseausTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_reseaus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Aucune',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Fossé, noues',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Drainage / Pompage',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Cours d\'eau',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Axe de ruissellement',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Autre',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_reseaus')->insert(
            [
                'nom' => 'Indéterminée',
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
        Schema::dropIfExists('hydrologie_reseaus');
    }
}
