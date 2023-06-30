<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeMaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_mares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('type_mares')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De prairie',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De culture',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De friche',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De forêt',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De marais',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De Carrière',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'Bassin routier ou de décantation',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De village, de ferme, de parc ou jardin',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De lisière',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'De tourbière',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_mares')->insert(
            [
                'nom' => 'indéterminé',
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
        Schema::dropIfExists('type_mares');
    }
}
