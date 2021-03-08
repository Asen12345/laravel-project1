<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('published')->default(false);
            $table->string('title')->nullable();
            $table->string('url_ru')->nullable();
            $table->string('url_en')->nullable();
            $table->text('announce')->nullable();
            $table->longText('text');
            $table->bigInteger('news_category_id');
            $table->string('source_name')->nullable();
            $table->string('source_url')->nullable();
            $table->boolean('vip')->default(false);
            $table->boolean('author_show')->default(true);
            $table->string('author_text_val')->nullable();
            $table->bigInteger('author_user_id')->nullable();
            $table->string('posted')->default('user');
            $table->boolean('yandex')->default(false);
            $table->boolean('new')->default(true);
            $table->date('published_at')->nullable();
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
        Schema::dropIfExists('news');
    }
}
