<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Faq;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->integer('dispensary_id')->unsigned()->nullable();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('priority');
            $table->enum('status', [Faq::ACTIVE, Faq::INACTIVE])->default(Faq::ACTIVE);
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
        Schema::dropIfExists('faqs');
    }
}
