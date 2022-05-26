<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hub\Deal;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('name');
            $table->mediumText('slug');
            $table->string('sku',255);
            $table->text('description')->nullable()->default(null);
            $table->enum('deal_type', [
                Deal::NORMAL,
                Deal::BUY_X,
                Deal::SPEND_X
            ])->default(Deal::NORMAL);
            $table->enum('discount_type', [
                Deal::FREE,
                Deal::AMOUNT,
                Deal::FIXED,
                Deal::PERCENT
            ])->default(Deal::PERCENT);
            $table->enum('applied_on', [
                Deal::TOTAL,
                Deal::PRODUCT,
                Deal::CATEGORY,
                Deal::BRAND,
            ])->nullable()->default(null);
            $table->enum('condition_on', [
                Deal::CART,
                Deal::PRODUCT,
                Deal::CATEGORY,
                Deal::BRAND
            ])->nullable()->default(null);
            $table->decimal('discount_value')->default(0.00);
            $table->integer('total_usage_limit')->default(0)->unsigned();
            $table->integer('per_user_limit')->default(0)->unsigned();
            $table->decimal('min_spend')->default(0.00);
            $table->decimal('max_spend')->default(0.00);
            $table->string('active_days', 50)->default('0')->comment('0 to 6 | 0 - Sunday to 6 - Saturday');
            $table->time('start_time')->default('00:00:00');
            $table->time('end_time')->default('12:00:00');
            $table->date('start_date');
            $table->date('end_date')->nullable()->default(null);
            $table->integer('number_of_x')->unsigned()->default(0);
            $table->integer('added_by')->unsigned()->default(0);
            $table->enum('status', [Deal::ACTIVE, Deal::INACTIVE])->default(Deal::ACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('deal_models', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->integer('deal_id')->unsigned();
            $table->string('type');
            $table->string('sub_type');
            $table->timestamps();
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
        Schema::dropIfExists('deal_models');
    }
}
