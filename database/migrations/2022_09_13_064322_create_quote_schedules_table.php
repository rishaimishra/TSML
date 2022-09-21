<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quote_id');
            $table->string('quantity');
            $table->string('pro_size');
            $table->date('to_date');
            $table->date('from_date');
            $table->bigInteger('kam_price')->nullable();
            $table->bigInteger('expected_price')->nullable();
            $table->string('plant');
            $table->string('location');
            $table->string('bill_to');
            $table->string('ship_to');
            $table->string('remarks');
            $table->string('delivery');
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
        Schema::dropIfExists('quote_schedules');
    }
}
