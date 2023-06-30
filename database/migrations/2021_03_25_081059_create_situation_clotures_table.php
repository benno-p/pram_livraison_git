<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSituationCloturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situation_clotures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });


        DB::table('situation_clotures')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_clotures')->insert(
            [
                'nom' => 'Non',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_clotures')->insert(
            [
                'nom' => 'En partie',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('situation_clotures')->insert(
            [
                'nom' => 'Totalement',
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
        Schema::dropIfExists('situation_clotures');
    }
}
