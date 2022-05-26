<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensaryTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_timings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_hour_set_id')->unsigned();
            $table->tinyInteger('day');
            $table->time('from_time')->default('00:00:00');
            $table->time('to_time')->default('23:30:00');
            $table->timestamps();
            $table->foreign('dispensary_hour_set_id')->references('id')->on('dispensary_hour_sets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispensary_timings');
    }
}
