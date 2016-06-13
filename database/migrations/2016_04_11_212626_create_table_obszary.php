<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableObszary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obszary', function (Blueprint $table) {
            $table->integer('id_analiza')->length(10)->unsigned();
            $table->string('obszar');
            $table->string('umiejetnosc');
            $table->string('nr_zadania');
            $table->foreign('id_analiza')->references('id')->on('analiza')->onDelete('cascade');
            $table->unique(array('id_analiza', 'obszar', 'umiejetnosc', 'nr_zadania'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('obszary');
    }
}
