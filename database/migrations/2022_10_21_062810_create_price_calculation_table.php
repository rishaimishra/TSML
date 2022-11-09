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
            $table->unsignedBigInteger('pro_id');
            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('sub_cat_id');
            $table->string('size')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('BPT_Price')->nullable(); 
            $table->string('Price_Premium')->nullable();
            $table->string('Misc_Expense')->nullable(); 
            $table->string('Interest_Rate')->nullable();
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
        Schema::dropIfExists('price_calculation');
    }
}
