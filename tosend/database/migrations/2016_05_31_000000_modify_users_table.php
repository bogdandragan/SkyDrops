<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->boolean('isAdmin')->nullable();
            $table->string('restore_code', 50)->nullable();
            $table->integer('coins')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('isAdmin');
            $table->dropColumn('restore_code');
            $table->dropColumn('coins');
        });
    }

}
