<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPostVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->bigInteger('blog_id')->unsigned();
            $table->bigInteger('post_id')->unsigned();
            $table->string('vote');
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
        Schema::dropIfExists('blog_post_votes');
    }
}
