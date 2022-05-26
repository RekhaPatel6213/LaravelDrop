<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Dispensary\SubscriptionPrice;

class CreateSubscriptionPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('stripe_price_id',100);
            $table->string('stripe_product_id', 100);
            $table->decimal('amount', 8, 2)->nullable()->default(0);
            $table->string('interval',20)->nullable()->default(0);
            $table->decimal('interval_count', 8, 2)->nullable()->default(0);
            $table->integer('trial_days')->nullable()->default(0);
            $table->integer('sms')->nullable()->default(0);
            $table->string('sms_group', 100)->nullable()->default(null);
            $table->enum('type', [SubscriptionPrice::SUBSCRIPTION, SubscriptionPrice::SMS])->default(SubscriptionPrice::SUBSCRIPTION);
            $table->string('recurring_type', 100);
            $table->enum('status', [SubscriptionPrice::ACTIVE, SubscriptionPrice::INACTIVE])->default(SubscriptionPrice::ACTIVE);
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
        Schema::dropIfExists('subscription_prices');
    }
}
