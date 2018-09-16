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
        //
        Schema::create('servers', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';
            $table->bigIncrements('server_id')->unsigned();
            $table->string('server_name', 60);
            $table->enum('server_type', array('Application Server', 'Database Server'));
            $table->enum('environment', array('Development', 'QA', 'Staging', 'Production'));
            $table->string('hostname', 60);
            $table->bigInteger('server_group_id')->unsigned();
            $table->enum('status', array('Not Monitored', 'Monitored', 'Maintenance'));
            $table->foreign('server_group_id')->references('server_group_id')->on('server_groups')->onDelete('cascade');
            $table->unique([
                'server_name',
            ], 'unq_server_name');
            $table->unique([
                'hostname',
            ], 'unq_hostname');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
}
