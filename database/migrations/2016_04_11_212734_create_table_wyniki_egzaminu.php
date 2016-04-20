<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWynikiEgzaminu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wyniki_egzaminu', function (Blueprint $table) {
            $table->integer('id_analiza')->length(10)->unsigned();
            $table->string('klasa');
            $table->string('kod_ucznia');
            $table->string('nr_zadania');
            $table->float('liczba_punktow');
            $table->integer('max_punktow');
            $table->foreign('id_analiza')->references('id')->on('analiza');
            $table->unique(array('id_analiza', 'klasa', 'kod_ucznia', 'nr_zadania'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wyniki_egzaminu');
    }
}
