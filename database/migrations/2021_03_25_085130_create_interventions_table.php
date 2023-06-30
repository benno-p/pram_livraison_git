<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('interventions')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Aucun',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Curage',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Reprofilage berge',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Bûcheronnage',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Débroussaillage',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Pose de clôture',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Aménagemet d\'abreuvoir',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Lutte contre espèces exotiques envahissantes',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Nettoyage déchets',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Arrachage de végétation',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Intervention sur fonctionnement hydraulique',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
            [
                'nom' => 'Fauchage tardif de la périphérie',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('interventions')->insert(
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
        Schema::dropIfExists('interventions');
    }
}
