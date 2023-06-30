<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcologieBoisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecologie_boisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '0 %',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '0 - 25 %',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '25 - 50 %',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '50 - 75 %',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '75 - 100 %',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('ecologie_boisements')->insert(
            [
                'nom' => '100 %',
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
        Schema::dropIfExists('ecologie_boisements');
    }
}
