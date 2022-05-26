<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use App\Models\Hub\SMSCampaign;

/**
 * Class CreateSMSCampaignsTable.
 */
class CreateSMSCampaignsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sms_campaigns', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->integer('added_by')->unsigned();
            $table->enum('patient_type',[config('constants.PATIENT_TYPE.BOTH'),config('constants.PATIENT_TYPEMEDICAL'),config('constants.PATIENT_TYPE.RECREATIONAL')]);
            $table->integer('segmentation');
            $table->json('territory_ids');
            $table->text('message');
            $table->enum('type_scheduled',SMSCampaign::TYPE_SCHEDULE);
            $table->date('schedule_date')->nullable();
            $table->json('schedule_time')->nullable();
            $table->enum('status',SMSCampaign::STATUS);
            $table->integer('total_customer');
            $table->integer('total_send')->default(0);
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
		Schema::dropIfExists('sms_campaigns');
	}
}
