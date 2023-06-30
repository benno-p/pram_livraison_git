<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuts', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 100);
            $table->string('nom_interne', 100);

            $table->timestamps();
        });

        DB::table('statuts')->insert(
            [
                'nom' => 'En attente de validation',
                'nom_interne' => 'en_attente_de_validation',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('statuts')->insert(
            [
                'nom' => 'Valider',
                'nom_interne' => 'valider',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('statuts')->insert(
            [
                'nom' => 'Refuser',
                'nom_interne' => 'refuser',
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
        Schema::dropIfExists('statuts');
    }
}
