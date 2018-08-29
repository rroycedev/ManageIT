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
            $table->increments('server_id');
            $table->string('server_name', 60);
            $table->string('hostname', 60);
            $table->integer('port')->unsigned();
            $table->integer('server_group_id')->unsigned();
            $table->unique([
                'server_name',
            ], 'unq_server_name');
            $table->unique([
                'hostname',
                'port',
            ], 'unq_hostname_port');
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
