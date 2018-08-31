<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseCconnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('database_connections', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';
            $table->integer('server_group_id')->unsigned();
            $table->string('username', 60);
            $table->binary('password');
            $table->integer('port')->unsigned();
            $table->primary('server_group_id');
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
        Schema::dropIfExists('database_connections');
    }
}
