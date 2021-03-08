<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNewsCategoriesAddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_categories', function(Blueprint $table) {
            $table->index('url_ru');
            $table->index('url_en');
            $table->index('sort');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_categories', function(Blueprint $table) {
            $table->dropIndex('news_categories_url_ru_index');
            $table->dropIndex('news_categories_url_en_index');
            $table->dropIndex('news_categories_sort_index');
            $table->dropIndex('news_categories_parent_id_index');
        });
    }
}
