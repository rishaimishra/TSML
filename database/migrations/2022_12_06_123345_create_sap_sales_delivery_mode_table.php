<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapSalesDeliveryModeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sap_delivery_mode', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('delivery_mode_code')->nullable();
            $table->string('delivery_mode_dec')->nullable();
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
        Schema::dropIfExists('sap_delivery_mode');
    }
}
