<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_privacy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('work_phone_show', 15)->default('all');
            $table->string('mobile_phone_show', 15)->default('all');
            $table->string('skype_show', 15)->default('all');
            $table->string('web_site_show', 15)->default('all');
            $table->string('work_email_show', 15)->default('all');
            $table->string('personal_email_show', 15)->default('all');
            $table->string('about_me_show', 15)->default('all');
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
        Schema::dropIfExists('user_privacy');
    }
}
