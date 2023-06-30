<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeProprietesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_proprietes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom', 150);
            $table->timestamps();
        });

        DB::table('type_proprietes')->insert(
            [
                'nom' => 'Non renseigné',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_proprietes')->insert(
            [
                'nom' => 'Public',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_proprietes')->insert(
            [
                'nom' => 'Privé',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_proprietes')->insert(
            [
                'nom' => 'Mixte',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('type_proprietes')->insert(
            [
                'nom' => 'Inconnu',
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
        Schema::dropIfExists('type_proprietes');
    }
}
