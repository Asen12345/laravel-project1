<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBlogPostVotesAddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_post_votes', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('blog_id');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_post_votes', function(Blueprint $table) {
            $table->dropIndex('blog_post_votes_user_id_index');
            $table->dropIndex('blog_post_votes_blog_id_index');
            $table->dropIndex('blog_post_votes_post_id_index');
        });
    }
}
