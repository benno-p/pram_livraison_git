<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadeEvolutionMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stade_evolution_mares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nom');
            $table->text('descriptif')->nullable();
            $table->text('chemin_image')->nullable();
            $table->timestamps();
        });

        DB::table('stade_evolution_mares')->insert(
            [
                'nom' => 'Non renseigné',
                'descriptif' => '',
                'chemin_image' => 'stade_mare_images/non_renseigne.png',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('stade_evolution_mares')->insert(
            [
                'nom' => 'Stade 1',
                'descriptif' => 'Hélophytes et hydrophytes enracinés sont absents ou commencent tout juste à s\'implanter et/ou la mare n\'est pas envasée.',
                'chemin_image' => 'stade_mare_images/stade_1.png',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('stade_evolution_mares')->insert(
            [
                'nom' => 'Stade 2',
                'descriptif' => 'Hélophytes et hydrophytes enracinés ont déjà colonisé une partie de la mare et/ou ma mare est peu envasée.',
                'chemin_image' => 'stade_mare_images/stade_2.png',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('stade_evolution_mares')->insert(
            [
                'nom' => 'Stade 3',
                'descriptif' => 'Hélophytes et hydrophytes enracinés ont envahi la totalité de la mare et/ou la mare est partiellement envasée.',
                'chemin_image' => 'stade_mare_images/stade_3.png',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('stade_evolution_mares')->insert(
            [
                'nom' => 'Stade 4',
                'descriptif' => 'La mare est quasiment comblée. Les ronces et saules la colonisent et/ou elle est très envasée.',
                'chemin_image' => 'stade_mare_images/stade_4.png',
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
        Schema::dropIfExists('stade_evolution_mares');
    }
}
