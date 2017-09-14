<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldhousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oldhouses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id');
            $table->string('title');
            $table->integer('sale_price');
            $table->integer('floor');
            $table->enum('decoration', ['精装', '简装', '毛坯']);
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
        Schema::drop('oldhouses');
    }
}
