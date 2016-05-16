<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('drops', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('user_id');
			$table->string('hash')->unique();;
			$table->string('title')->nullable();
			$table->timestamp('expires_at')->nullable();
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
		Schema::drop('drops');
	}

}
