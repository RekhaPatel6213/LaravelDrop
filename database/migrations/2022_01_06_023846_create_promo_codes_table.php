<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hub\PromoCode;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('applicable_to', 10);
            $table->string('promo_code', 50);
            $table->string('promo_overview');
            $table->enum('discount_type', [PromoCode::PERCENTAGE, PromoCode::FIXED])->default(PromoCode::PERCENTAGE);
            $table->double('discount_value', 8,2)->default(0.00);
            $table->integer('product_id')->unsigned()->default(0);
            $table->enum('applies_to', [PromoCode::PRODUCT, PromoCode::ORDER])->default(PromoCode::ORDER);
            $table->enum('use_minimum', [PromoCode::NONE, PromoCode::AMOUNT])->default(PromoCode::NONE);
            $table->double('minimum_amount', 8,2)->default(0.00);
            $table->boolean('unlimited')->default(true);
            $table->smallInteger('usage_limit')->unsigned()->default(0);
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time')->nullable()->default(null);
            $table->enum('status', [PromoCode::ACTIVE, PromoCode::INACTIVE])->default(PromoCode::ACTIVE);
            $table->mediumInteger('used_count')->unsigned()->default(0);
            $table->integer('added_by')->unsigned();
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
        Schema::dropIfExists('promo_codes');
    }
}
