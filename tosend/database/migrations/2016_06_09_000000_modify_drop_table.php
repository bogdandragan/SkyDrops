<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDropTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drops', function(Blueprint $table)
        {
            $table->boolean('forUpload')->nullable();
            $table->boolean('wasDownloaded')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drops', function($table) {
            $table->dropColumn('forUpload');
            $table->dropColumn('wasDownloaded');
        });
    }

}
