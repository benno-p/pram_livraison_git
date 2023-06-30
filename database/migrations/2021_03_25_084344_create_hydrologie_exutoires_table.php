<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologieExutoiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_exutoires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('hydrologie_exutoires')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_exutoires')->insert(
            [
                'nom' => 'Surverse',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_exutoires')->insert(
            [
                'nom' => 'Débit de fuite',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_exutoires')->insert(
            [
                'nom' => 'Débordement',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_exutoires')->insert(
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
        Schema::dropIfExists('hydrologie_exutoires');
    }
}
