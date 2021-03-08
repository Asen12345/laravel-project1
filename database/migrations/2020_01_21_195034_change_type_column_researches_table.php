<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeColumnResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
            $table->integer('page')->nullable()->change();
            $table->string('format')->nullable()->change();
            $table->string('language')->nullable()->change();
            $table->string('published_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researches', function (Blueprint $table) {
            $table->text('content')->change();
            $table->integer('page')->change();
            $table->string('format')->change();
            $table->string('language')->change();
            $table->timestamp('published_at')->change();
        });
    }
}
