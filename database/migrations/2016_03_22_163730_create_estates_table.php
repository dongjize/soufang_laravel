<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', ['住宅', '别墅', '商铺']);
            $table->integer('district_id');
            $table->double('longitude');
            $table->double('latitude');
            $table->string('address');
            $table->integer('avg_price');
            $table->enum('is_new', [1, 0]);
            $table->longText('pictures');
            $table->string('developer');
            $table->date('open_date');
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
        Schema::drop('estates');
    }
}
