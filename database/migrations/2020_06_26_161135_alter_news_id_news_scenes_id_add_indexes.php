<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsIdNewsScenesIdAddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_id_news_scene_id', function(Blueprint $table) {
            $table->index('news_id');
            $table->index('news_scene_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_id_news_scene_id', function(Blueprint $table) {
            $table->dropIndex('news_id');
            $table->dropIndex('news_scene_id');
        });
    }
}
