<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->bigInteger('shop_category_id');
            $table->longText('annotation');
            $table->longText('content');
            $table->longText('table')->nullable();
            $table->string('image')->nullable();
            $table->string('demo_file')->nullable();
            $table->string('file')->nullable();
            $table->timestamp('published_at');
            $table->integer('page');
            $table->string('format');
            $table->string('language');
            $table->integer('price')->unsigned();
            $table->bigInteger('researches_author_id')->unsigned();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('download')->default(0);
            $table->boolean('main');
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
        Schema::dropIfExists('researches');
    }
}
