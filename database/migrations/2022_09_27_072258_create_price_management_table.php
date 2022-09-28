<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_management', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('pro_id');
            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('sub_cat_id');
            $table->string('size')->nullable();
            $table->string('basic_price')->comment('per MT')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive');
            $table->foreign('pro_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('cat_id')->references('id')->on('categorys')->onDelete('cascade'); 
            $table->foreign('sub_cat_id')->references('id')->on('sub_categorys')->onDelete('cascade');
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
        Schema::dropIfExists('price_management');
    }
}
