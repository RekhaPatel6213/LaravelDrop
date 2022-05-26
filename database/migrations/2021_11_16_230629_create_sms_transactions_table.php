<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispensary_id');
            $table->string('month', 8)->nullable();
            $table->enum('type', ['deposit', 'withdraw'])->default('withdraw');
            $table->decimal('amount', 64, 0);
            $table->decimal('used_amount', 64, 0)->default(0);
            $table->timestamp('expiry_date')->nullable();
            $table->boolean('confirmed');
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('sms_transactions');
    }
}

