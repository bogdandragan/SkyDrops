<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentResultsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentResults', function(Blueprint $table)
        {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->string('payment_id', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('txn_id', 100)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 250)->nullable();
            $table->string('payer_email', 250)->nullable();
            $table->string('mc_gross', 50)->nullable();
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
        Schema::drop('paymentResults');
    }

}
