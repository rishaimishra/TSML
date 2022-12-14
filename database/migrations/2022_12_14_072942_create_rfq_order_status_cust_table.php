<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqOrderStatusCustTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_order_status_cust', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('rfq_no');
            $table->string('rfq_submited')->nullable(); 
            $table->string('quoted_by_tsml')->nullable();
            $table->string('under_negotiation')->nullable();
            $table->string('final_quoted_by_tsml')->nullable(); 
            $table->string('rfq_closed')->nullable();
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
        Schema::dropIfExists('rfq_order_status_cust');
    }
}
