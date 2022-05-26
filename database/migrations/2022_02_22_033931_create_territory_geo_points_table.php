<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerritoryGeoPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('territory_geo_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('territory_id');
            $table->json('geo_points');
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
        Schema::dropIfExists('territory_geo_points');
    }
}
