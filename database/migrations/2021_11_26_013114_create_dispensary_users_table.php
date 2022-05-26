<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispensaryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 255);
            $table->string('password')->nullable()->default(null);
            $table->string('phone', 255);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('dispensary_users');
    }
}
