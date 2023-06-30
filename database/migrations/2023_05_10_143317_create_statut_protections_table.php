<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatutProtectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statut_protections', function (Blueprint $table) {
            $table->id();
            $table->string('nom',20);
            $table->string('nom_interne', 20);
            $table->timestamps();
        });

        DB::table('statut_protections')->insert(
            [
                'nom' => 'Article 2',
                'nom_interne' => 'article_2',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('statut_protections')->insert(
            [
                'nom' => 'Article 3',
                'nom_interne' => 'article_3',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('statut_protections')->insert(
            [
                'nom' => 'Article 4',
                'nom_interne' => 'article_4',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('statut_protections')->insert(
            [
                'nom' => 'Article 5',
                'nom_interne' => 'article_5',
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
        Schema::dropIfExists('statut_protections');
    }
}
