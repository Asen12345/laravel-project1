<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unique()->unsigned();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->string('image')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('skype')->nullable();
            $table->string('web_site')->nullable();
            $table->string('work_email')->nullable();
            $table->string('personal_email')->nullable();
            $table->text('about_me')->nullable();
            $table->string('face_book')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('v_kontakte')->nullable();
            $table->string('odnoklasniki')->nullable();
            $table->string('company_post')->nullable();
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
        Schema::dropIfExists('user_social_profiles');
    }
}
