<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedDropsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sharedDrops', function(Blueprint $table)
        {
            $table->increments('id');
            $table->bigInteger('drop_id');
            $table->string('email', 100)->nullable();
            $table->string('message', 250)->nullable();
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
        Schema::drop('sharedDrops');
    }

}
