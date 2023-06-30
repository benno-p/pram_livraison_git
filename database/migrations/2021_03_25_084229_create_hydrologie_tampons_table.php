<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieTamponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_tampons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_tampons')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_tampons')->insert(
            [
                'nom' => 'Oui',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_tampons')->insert(
            [
                'nom' => 'Non',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_tampons')->insert(
            [
                'nom' => 'Indeterminé',
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
        Schema::dropIfExists('hydrologie_tampons');
    }
}
