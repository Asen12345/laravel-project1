<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('open_password')->nullable();
            $table->boolean('invitations')->default(false);
            $table->enum('permission', ['expert', 'company', 'social']);
            $table->boolean('active')->default(false);
            $table->boolean('block')->default(false);
            $table->boolean('private')->default(false);
            $table->boolean('notifications_subscribed')->default(false);
            $table->rememberToken();
            $table->timestamp('active_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
