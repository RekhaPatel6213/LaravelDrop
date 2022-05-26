<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Dispensary\Dispensary;

class CreateDispensariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('email', 255);
            $table->string('phone', 12);
            $table->string('address');
            $table->string('app_name', 100)->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->string('state_licence')->nullable()->default(null);
            $table->string('timezone', 100)->nullable()->default(null);
            $table->string('own_domain', 255);
            $table->integer('admin_user_id')->unsigned();
            $table->string('bitly_link', 255);
            $table->decimal('setup_fee', 8, 2);
            $table->string('services', 8);
            $table->enum('billing_prompt', [Dispensary::MANUALLY_BILLED, Dispensary::CARD])->default(Dispensary::MANUALLY_BILLED);
            $table->enum('service_fee_enabled', [Dispensary::ENABLED, Dispensary::DISABLED])->default(Dispensary::ENABLED);
            $table->enum('country_code', [Dispensary::US, Dispensary::CA])->default(Dispensary::US);
            $table->decimal('service_fee_amount', 8,2);
            $table->string('subscription_type', 255)->nullable()->default(null);
            $table->enum('status', Dispensary::DEFAULT_STATUSES)->default(Dispensary::PENDING);
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispensaries');
    }
}
