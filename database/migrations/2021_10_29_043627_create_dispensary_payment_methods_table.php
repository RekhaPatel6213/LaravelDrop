<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Dispensary\DispensaryPaymentMethod;

class CreateDispensaryPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensary_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispensary_id')->unsigned();
            $table->string('payment_slug', 50);
            $table->string('payment_title', 50);
            $table->string('description')->nullable()->default(null);
            $table->enum('enable_tip', ['YES', 'NO'])->default('NO');
            $table->enum('enable_cash', ['YES', 'NO'])->default('NO');
            $table->enum('status', [
                DispensaryPaymentMethod::ACTIVE,
                DispensaryPaymentMethod::INACTIVE
            ])->default(DispensaryPaymentMethod::ACTIVE);
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
        Schema::dropIfExists('dispensary_payment_methods');
    }
}
