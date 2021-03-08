<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('shopping_cart_id');
            $table->string('type')->default('individual');
            $table->string('company')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('shopping_payments');
    }
}
