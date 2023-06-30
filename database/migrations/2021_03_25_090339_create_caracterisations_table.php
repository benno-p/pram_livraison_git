<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracterisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracterisations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 50);
            $table->string('nom_interne', 50);
            $table->string('couleur', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('caracterisations')->insert(
            [
                'nom' => 'En attente de validation',
                'nom_interne' => 'en_attente_de_validation',
                'couleur' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracterisations')->insert(
            [
                'nom' => 'Vue',
                'nom_interne' => 'vue',
                'couleur' => '#84B826',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracterisations')->insert(
            [
                'nom' => 'Caractérisée',
                'nom_interne' => 'caracterisee',
                'couleur' => '#09589D',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracterisations')->insert(
            [
                'nom' => 'Disparue',
                'nom_interne' => 'disparue',
                'couleur' => '#343a40',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracterisations')->insert(
            [
                'nom' => 'Potentielle',
                'nom_interne' => 'potentielle',
                'couleur' => '#dc3545',
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
        Schema::dropIfExists('caracterisations');
    }
}
