<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_calculation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('BPT_Price')->nullable(); 
            $table->string('Price_Premium')->nullable();
            $table->string('Misc_Expense')->nullable();
            $table->string('Credit_Cost_For_30_days')->nullable(); 
            $table->string('Credit_Cost_For_40_days')->nullable(); 
            $table->string('Interest_Rate')->nullable();
            $table->string('CAM_Discount')->nullable();  
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive|3=Delete');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('price_calculation');
    }
}
