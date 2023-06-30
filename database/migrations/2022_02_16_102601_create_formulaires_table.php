<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('nom_interne', 150)->nullable();
            $table->timestamps();
        });

        DB::table('formulaires')->insert(
            [
                'nom' => 'Code OFB',
                'nom_interne' => 'code_ofb',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('formulaires')->insert(
            [
                'nom' => 'Statut protection',
                'nom_interne' => 'statut_protection',
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
        Schema::dropIfExists('formulaires');
    }
}
