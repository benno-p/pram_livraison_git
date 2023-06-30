<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationTopographiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situation_topographies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('situation_topographies')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_topographies')->insert(
            [
                'nom' => 'Plateau',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_topographies')->insert(
            [
                'nom' => 'Versant',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_topographies')->insert(
            [
                'nom' => 'Fond de vallée',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_topographies')->insert(
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
        Schema::dropIfExists('situation_topographies');
    }
}
