<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audits', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('dispensary_id')->unsigned();
			$table->morphs('model');
			$table->json('products')->nullable();
			$table->integer('created_by')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('dispensary_id')->references('id')->on('dispensaries')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('created_by')->references('id')->on('dispensary_users')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audits');
	}
}
