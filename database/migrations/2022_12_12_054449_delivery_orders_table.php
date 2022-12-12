<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeliveryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('so_no')->nullable();
            $table->string('do_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->unsignedBigInteger('material_grade')->nullable()->comment('sub_category_id');
            $table->string('do_quantity')->nullable();
            $table->dateTime('despatch_date')->nullable();
            $table->string('truck_no')->nullable();
            $table->string('driver_no')->nullable();
            $table->string('premarks')->nullable();
            $table->string('lr_file')->nullable();
            $table->string('e_waybill_file')->nullable();
            $table->string('test_certificate_file')->nullable();
            $table->string('e_invoice_file')->nullable();
            $table->string('misc_doc_file')->nullable();
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
        Schema::dropIfExists('delivery_orders');
    }
}
