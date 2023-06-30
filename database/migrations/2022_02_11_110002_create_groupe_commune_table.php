<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupeCommuneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupe_commune', function (Blueprint $table) {
            $table->id();

            $table->integer('groupe_id')->nullable()->index();
            $table->foreign('groupe_id')->references('id')->on('groupes');

            $table->integer('commune_id')->nullable()->index();
            $table->foreign('commune_id')->references('id')->on('communes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groupe_commune');
    }
}
