<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThresholdLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threshold_limits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Price_Premium')->nullable();
            $table->string('Misc_Expense')->nullable();
            $table->string('Delivery_Cost')->nullable();
            $table->string('Credit_Cost_For_30_days')->nullable(); 
            $table->string('Credit_Cost_For_40_days')->nullable();  
            $table->string('CAM_Discount')->nullable();  
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive|3=Delete');
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
        Schema::dropIfExists('threshold_limits');
    }
}
