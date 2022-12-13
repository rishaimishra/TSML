<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('transact_id');
            $table->string('so_no')->nullable();
            $table->string('co_no');
            $table->string('po_no');
            $table->string('pay_proc');
            $table->string('fin_doc_no');
            $table->integer('status');
            $table->string('user_id');
            $table->string('order_type');
            $table->string('sales_org');
            $table->string('dis_chnl');
            $table->string('division');
            $table->string('sales_ofc');
            $table->string('sales_grp');
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
        Schema::dropIfExists('sales_orders');
    }
}
