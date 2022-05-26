<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensaryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('dispensary_id')->unsigned();
            $table->integer('taxon_id')->unsigned();
            $table->string('description')->nullable()->default(null);
            $table->smallInteger('priority')->nullable()->default(null);
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
        Schema::dropIfExists('dispensary_categories');
    }
}
