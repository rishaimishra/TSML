<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqOrderStatusKamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_order_status_kam', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('rfq_no');
            $table->string('rfq_submited')->nullable(); 
            $table->string('approve_pending_from_sales')->nullable();
            $table->string('rebutted_by_sales_plaing')->nullable();
            $table->string('requated')->nullable();
            $table->string('under_negotiation')->nullable();
            $table->string('quote_closed')->nullable();
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
        Schema::dropIfExists('rfq_order_status_kam');
    }
}
