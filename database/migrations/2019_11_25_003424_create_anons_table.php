<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->date('date')->nullable();
            $table->date('will_end')->nullable();
            $table->string('place')->nullable();
            $table->string('organizer')->nullable();
            $table->text('text')->nullable();
            $table->string('reg_linc')->nullable();
            $table->string('price')->nullable();
            $table->boolean('main')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('new')->default(true);
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
        Schema::dropIfExists('anons');
    }
}
