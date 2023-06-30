<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationBatisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situation_batis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Aucun',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Fond empierré',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Muret',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Ponton',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Enrochement',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_batis')->insert(
            [
                'nom' => 'Autre',
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
        Schema::dropIfExists('situation_batis');
    }
}
