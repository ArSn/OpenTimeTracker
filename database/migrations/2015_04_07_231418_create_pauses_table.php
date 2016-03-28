<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePausesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pauses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('workday_id');
			$table->dateTime('start')->useCurrent();
			$table->dateTime('end')->nullable();
			$table->nullableTimestamps();

			$table->foreign('workday_id')
				->references('id')
				->on('workdays')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pauses');
	}

}
