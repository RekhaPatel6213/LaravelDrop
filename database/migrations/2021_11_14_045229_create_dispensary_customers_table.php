<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Customer\DispensaryCustomer;

class CreateDispensaryCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->mediumText('address')->nullable()->default(null);
            $table->enum('patient_type', [
                DispensaryCustomer::MEDICAL,
                DispensaryCustomer::RECREATIONAL
            ])->default(DispensaryCustomer::MEDICAL);
            $table->string('patient_number')->nullable();
            $table->string('patient_doctor')->nullable();
            $table->date('medical_expire_date')->nullable();
            $table->integer('territory_id')->unsigned();
            $table->enum('status', [
                DispensaryCustomer::ACTIVE,
                DispensaryCustomer::BLOCKED
            ])->default(DispensaryCustomer::ACTIVE);
            $table->enum('verify_status', [
                DispensaryCustomer::VERIFIED,
                DispensaryCustomer::UNVERIFIED,
                DispensaryCustomer::DECLINED
            ])->default(DispensaryCustomer::UNVERIFIED);
            $table->boolean('sms_enabled')->default(true);
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
        Schema::dropIfExists('dispensary_customers');
    }
}
