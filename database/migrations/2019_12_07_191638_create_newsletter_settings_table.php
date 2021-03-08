<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('weekdays')->nullable();
            $table->time('send_time')->nullable();
            $table->string('email')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('unsubscribe_text')->nullable();
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
        Schema::dropIfExists('newsletter_settings');
    }
}
