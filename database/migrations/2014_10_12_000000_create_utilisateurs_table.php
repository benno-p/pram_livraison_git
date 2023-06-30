<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->string('prenom', 150);
            $table->string('email', 250)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->tinyInteger('actif');

            $table->integer('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles');

            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('utilisateurs')->insert(
            [
                'nom' => 'Cen',
                'prenom' => 'User',
                'password' => '$2y$10$fuM4ZnOKn47NwiMMYKwxcu6DE1jPl8DqYyWHAHv/2FcKMgKye9cum',
                'email' => 'user@cen.fr',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'role_id' => 1,
            ]
        );

        // DB::table('utilisateurs')->insert(
        //     [
        //         'nom' => 'Kennel',
        //         'prenom' => 'Julien',
        //         'password' => '$2y$10$fuM4ZnOKn47NwiMMYKwxcu6DE1jPl8DqYyWHAHv/2FcKMgKye9cum',
        //         'email' => 'j.kennel@cen-lorraine.fr',
        //         'actif' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'role_id' => 1,
        //     ]
        // );

        // DB::table('utilisateurs')->insert(
        //     [
        //         'nom' => 'Demerges',
        //         'prenom' => 'David',
        //         'password' => '$2y$10$fuM4ZnOKn47NwiMMYKwxcu6DE1jPl8DqYyWHAHv/2FcKMgKye9cum',
        //         'email' => 'd.demerges@cen-lorraine.fr',
        //         'actif' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'role_id' => 1,
        //     ]
        // );

        // DB::table('utilisateurs')->insert(
        //     [
        //         'nom' => 'Mori',
        //         'prenom' => 'Quentin',
        //         'password' => '$2y$10$fuM4ZnOKn47NwiMMYKwxcu6DE1jPl8DqYyWHAHv/2FcKMgKye9cum',
        //         'email' => 'q.mori@cen-lorraine.fr',
        //         'actif' => 1,
        //         'created_at' => date('Y-m-d H:i:s'),
        //         // 'role_id' => 4,
        //         'role_id' => 1,
        //     ]
        // );




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
}
