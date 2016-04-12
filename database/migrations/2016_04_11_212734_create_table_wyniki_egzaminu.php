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
            $table->integer('nr_ucznia');
            $table->string('kod_ucznia');
            $table->string('klasa');
            $table->string('plec');
            $table->string('dysleksja');
            $table->string('lokalizacja');
            $table->string('id_analiza');
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
