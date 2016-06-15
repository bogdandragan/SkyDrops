<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCoinsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersCoins', function(Blueprint $table)
        {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->boolean('isAdded')->nullable();
            $table->bigInteger('drop_id')->nullable();
            $table->integer('amount');
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
        Schema::drop('usersCoins');
    }

}
