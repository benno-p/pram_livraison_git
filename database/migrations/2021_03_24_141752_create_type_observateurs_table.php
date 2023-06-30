<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeObservateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_observateurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('type_observateurs')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_observateurs')->insert(
            [
                'nom' => 'Propriétaire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_observateurs')->insert(
            [
                'nom' => 'Locataire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_observateurs')->insert(
            [
                'nom' => 'Gestionnaire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_observateurs')->insert(
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
        Schema::dropIfExists('type_observateurs');
    }
}
