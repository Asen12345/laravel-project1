<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNewsAddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function(Blueprint $table) {
            $table->index('published');
            $table->index('news_category_id');
            $table->index('author_user_id');
            $table->index('published_at');
            $table->index('url_ru');
            $table->index('url_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function(Blueprint $table) {
            $table->dropIndex('news_published_index');
            $table->dropIndex('news_news_category_id_index');
            $table->dropIndex('news_author_user_id_index');
            $table->dropIndex('news_published_at_index');
            $table->dropIndex('news_url_ru_index');
            $table->dropIndex('news_url_en_index');
        });
    }
}
