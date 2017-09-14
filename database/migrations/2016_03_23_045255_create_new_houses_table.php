<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newhouses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_id');
            $table->string('name');
            $table->string('price');
            $table->enum('decoration', ['精装', '简装', '毛坯']);
            $table->integer('storage');
            $table->timestamps();
            /**
             *  * name 户型名
             * estate_id 所属楼盘/小区
             * area 面积
             * room_count x室
             * parlour_count x厅
             * toilet_count x卫
             * kitchen_count x厨
             * photos 图片列表
             */
            /**
             * $table->increments('id');
            $table->integer('house_id');
            $table->string('title');
            $table->integer('sale_price');
            $table->integer('floor');
            $table->enum('decoration', ['精装', '简装', '毛坯']);
            $table->timestamps();
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newhouses');
    }
}
