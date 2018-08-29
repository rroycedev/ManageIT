<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ThresholdProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('threshold_profiles', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';
            $table->increments('profile_id');
            $table->string('profile_name', 60);
            $table->string('description', 100);
            $table->enum('profile_type', array('CPU', 'Memory'));
            $table - integer('warning_level')->unsigned();
            $table->integer('error_level')->unsigned();
            $table->unique([
                'profile_name',
            ], 'unq_threshold_profiles_profile_name');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threshold_profiles');
    }
}
