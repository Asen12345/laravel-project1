<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCategoryResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_category_researches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shop_category_id');
            $table->bigInteger('researches_id');
            $table->timestamps();
        });
        Schema::table('researches', function (Blueprint $table) {
            $table->dropColumn('shop_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_category_researches');
        Schema::table('researches', function (Blueprint $table) {
            $table->bigInteger('shop_category_id')->default(1);
        });
    }
}
