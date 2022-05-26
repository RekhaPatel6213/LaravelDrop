<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->integer('zip_code');
            $table->string('city');
            $table->string('state');
            $table->string('short_state');
            $table->string('country');
            $table->string('country_code');
            $table->decimal('lat', $precision = 12, $scale = 8);
            $table->decimal('lng', $precision = 12, $scale = 8);
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
        Schema::dropIfExists('locations');
    }
}
