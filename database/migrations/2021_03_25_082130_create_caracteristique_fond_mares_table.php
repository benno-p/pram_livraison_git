<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracteristiqueFondMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracteristique_fond_mares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('caracteristique_fond_mares')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_fond_mares')->insert(
            [
                'nom' => 'Matériau naturel',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_fond_mares')->insert(
            [
                'nom' => 'Béton',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_fond_mares')->insert(
            [
                'nom' => 'Bâche',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('caracteristique_fond_mares')->insert(
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
        Schema::dropIfExists('caracteristique_fond_mares');
    }
}
