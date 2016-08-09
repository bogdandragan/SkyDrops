<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropStatisticTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropStatistic', function(Blueprint $table)
        {
            $table->increments('id');
            $table->bigInteger('drop_id');
            $table->string('userAgent', 150)->nullable();
            $table->string('ip', 20)->nullable();
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
        Schema::drop('dropStatistic');
    }

}
