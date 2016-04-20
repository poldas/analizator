<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUczniowie extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uczniowie', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nr_ucznia');
            $table->integer('id_analiza')->length(10)->unsigned();
            $table->string('kod_ucznia');
            $table->string('klasa');
            $table->string('plec');
            $table->string('dysleksja');
            $table->string('lokalizacja');
            $table->timestamps();
            $table->foreign('id_analiza')->references('id')->on('analiza');
            $table->unique(array('id_analiza', 'kod_ucznia'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('uczniowie');
    }
}
