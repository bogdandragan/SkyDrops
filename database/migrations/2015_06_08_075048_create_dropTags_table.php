<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dropTags', function(Blueprint $table)
                {
                        $table->increments('id');
                        $table->bigInteger('drop_id');
                        $table->bigInteger('tag_id');
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
		Schema::drop('dropTags');
	}

}
