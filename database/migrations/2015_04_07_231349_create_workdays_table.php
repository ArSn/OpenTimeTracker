<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkdaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workdays', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->dateTime('start');
			$table->dateTime('end')->nullable();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
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
		Schema::drop('workdays');
	}

}
