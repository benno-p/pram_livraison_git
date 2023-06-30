<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupeIntercommunaliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupe_intercommunalite', function (Blueprint $table) {
            $table->id();

            $table->integer('groupe_id')->nullable()->index();
            $table->foreign('groupe_id')->references('id')->on('groupes');

            $table->integer('intercommunalite_id')->nullable()->index();
            $table->foreign('intercommunalite_id')->references('id')->on('intercommunalites');

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
        Schema::dropIfExists('groupe_intercommunalite');
    }
}
