<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAnaliza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analiza', function (Blueprint $table) {
            $table->increments('id')->length(10)->unsigned();
            $table->string('nazwa', 50);
            $table->string('file_path', 150);
            $table->char('status', 5);
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
        Schema::drop('analiza');
    }
}
