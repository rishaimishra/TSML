<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('product_id');
            $table->string('rfq_no');
            $table->integer('quantity');
            $table->bigInteger('kam_price')->nullable();
            $table->bigInteger('expected_price')->nullable();
            $table->string('plant');
            $table->string('location');
            $table->integer('kam_status')->comment('0=ongoing,1=approved')->default('0');
            $table->integer('cus_status')->comment('0=ongoing,1=approved')->default('0');
            $table->softDeletes();
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
        Schema::dropIfExists('quotes');
    }
}
