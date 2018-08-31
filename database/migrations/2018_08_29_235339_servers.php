<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Servers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->enum('status', array('Not Monitored', 'Monitored', 'Maintenance'));
            $table->dropColumn('port');
            $table->foreign('server_group_id')->references('server_group_id')->on('server_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->integer('port')->unsigned()->after('hostname');
            $table->dropForeign(['server_group_id']);
        });
    }
}
