<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerritoryModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territory_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('territory_id')->unsigned();
            $table->morphs('module');
            $table->timestamps();
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('territory_modules');
    }
}
