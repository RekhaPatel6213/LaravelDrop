<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Dispensary\LoyaltyProgram;

class CreateLoyaltyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('name', 255);
            $table->integer('points')->default(0);
            $table->boolean('is_default')->default(false);
            $table->time('start_time')->default('00:00:00');
            $table->time('end_time')->default('12:00:00');
            $table->enum('type', [
                LoyaltyProgram::STANDARD_LOYALTY,
                LoyaltyProgram::NEW_LOYALTY,
                LoyaltyProgram::BIRTHDAY,
                LoyaltyProgram::REFERRAL,
                LoyaltyProgram::TIME_BASED,
            ])->default(LoyaltyProgram::TIME_BASED);
            $table->enum('schedule', [
                LoyaltyProgram::WEEKLY,
                LoyaltyProgram::BI_WEEKLY,
                LoyaltyProgram::MONTHLY,
                LoyaltyProgram::MANUALLY,
            ])->default(LoyaltyProgram::WEEKLY);
            $table->string('active_days', 50)->default('0')->comment('0 to 6 | 0 - Sunday to 6 - Saturday');
            $table->enum('status', [LoyaltyProgram::ACTIVE, LoyaltyProgram::DISABLED])->default(LoyaltyProgram::ACTIVE);
            $table->mediumText('custom_message')->nullable()->default(null);
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
        Schema::dropIfExists('loyalty_programs');
    }
}
