<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydrologiePresencePoissonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydrologie_presence_poissons', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->timestamps();
        });

        DB::table('hydrologie_presence_poissons')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_presence_poissons')->insert(
            [
                'nom' => 'Oui',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('hydrologie_presence_poissons')->insert(
            [
                'nom' => 'Non',
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
        Schema::dropIfExists('hydrologie_presence_poissons');
    }
}
