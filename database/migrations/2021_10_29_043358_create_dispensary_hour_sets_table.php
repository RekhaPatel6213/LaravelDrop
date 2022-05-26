<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensaryHourSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_hour_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('name', 255);
            $table->timestamps();
            $table->foreign('dispensary_id')->references('id')->on('dispensaries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispensary_hour_sets');
    }
}
