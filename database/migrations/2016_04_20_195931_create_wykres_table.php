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
            $table->string('id_chart');
            $table->string('chart_name');
            $table->string('category');
            $table->string('xaxis_name');
            $table->string('yaxis_name');
            $table->string('options');
            $table->string('series');
            $table->timestamps();
            $table->integer('id_analiza')->length(10)->unsigned();

            $table->foreign('id_analiza')->references('id')->on('analiza')->onDelete('cascade');
            $table->unique(array('id_analiza', 'id_chart'));
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
