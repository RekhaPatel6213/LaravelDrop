<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('name', 255);
            $table->integer('metrc_location_id')->unsigned()->nullable();
            $table->enum('is_sale', ['YES', 'NO']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dispensary_id')->references('id')->on('dispensaries')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('model_inventories', function(Blueprint $table) {
            $table->integer('inventory_id')->unsigned();
            $table->morphs('model');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('inventory_id')->references('id')->on('inventories')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['inventory_id', 'model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('model_inventories');
    }
}
