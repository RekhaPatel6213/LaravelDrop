<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Driver\DriverUser;

class CreateDriverUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_users', function (Blueprint $table) {
            $table->id();
            $table->integer('dispensary_id')->unsigned();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->string('password')->nullable()->default(null);
            $table->enum('vehicle_type', [
                DriverUser::TRUCK,
                DriverUser::CAR,
                DriverUser::MOTORCYCLE,
                DriverUser::BIKE,
                DriverUser::WALK
            ])->default(DriverUser::CAR);
            $table->enum('status', [
                DriverUser::OFFLINE,
                DriverUser::ONLINE,
                DriverUser::IDLE
            ])->default(DriverUser::OFFLINE);
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
        Schema::dropIfExists('driver_users');
    }
}
