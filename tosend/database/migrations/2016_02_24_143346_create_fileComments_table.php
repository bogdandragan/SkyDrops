<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('fileComments', function(Blueprint $table)
                {
                        $table->increments('id');
                        $table->bigInteger('file_id');
			$table->bigInteger('user_id');
                        $table->string('text');
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
		Schema::drop('fileComments');
	}

}
