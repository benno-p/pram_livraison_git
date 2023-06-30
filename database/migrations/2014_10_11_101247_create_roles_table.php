<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 100);
            $table->string('nom_interne', 100)->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert(
            [
                'nom' => 'Développeur',
                'nom_interne' => 'developpeur',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('roles')->insert(
            [
                'nom' => 'Gestionnaire',
                'nom_interne' => 'gestionnaire',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('roles')->insert(
            [
                'nom' => 'Utilisateur',
                'nom_interne' => 'utilisateur',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('roles')->insert(
            [
                'nom' => 'Administrateur',
                'nom_interne' => 'administrateur',
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
        Schema::dropIfExists('roles');
    }
}
