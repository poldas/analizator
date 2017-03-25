<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWykresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wykresy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_analiza')->length(10)->unsigned();
            $table->string('name');
            $table->longText('series');
            $table->longText('options')->nullable();
            $table->string('labels');
            $table->string('tags');
            $table->text('opis');
            $table->foreign('id_analiza')->references('id')->on('analiza')->onDelete('cascade');
            $table->unique(array('id_analiza', 'id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wykresy');
    }
}
