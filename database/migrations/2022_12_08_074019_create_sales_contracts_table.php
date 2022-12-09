<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_no');
            $table->string('sc_no')->nullable();
            $table->date('sc_dt')->nullable();
            $table->string('contract_ty');
            $table->string('sales_grp');
            $table->string('qty_cont');
            $table->string('net_val');
            $table->string('sold_to_party');
            $table->string('ship_to_party');
            $table->string('cus_ref');
            $table->string('cus_ref_dt');
            $table->string('shp_cond');
            $table->string('sales_org');
            $table->string('dis_chnl');
            $table->string('div');
            $table->string('sales_ofc');
            $table->string('cost_ref');
            $table->integer('status');
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
        Schema::dropIfExists('sales_contracts');
    }
}
