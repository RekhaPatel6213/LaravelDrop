<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispensary_id');
            $table->string('stripe_invoice_id')->unique();
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('invoice_pdf')->nullable();
            $table->string('status')->nullable();
            $table->decimal('amount', 8, 2)->nullable()->default(0);
            $table->text('description')->nullable();
            $table->date('invoice_date')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
