<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cat_id'); 
            $table->unsignedBigInteger('sub_cat_id'); 
            $table->string('pro_name');
            $table->text('pro_desc')->nullable();
            $table->string('Cr')->nullable()->comment('Chromium');
            $table->string('C')->nullable()->comment('Carbon');
            $table->string('Phos')->nullable()->comment('Phosphorus ');
            $table->string('S')->nullable()->comment('Sulfur');
            $table->string('Si')->nullable()->comment('Silicon');
            $table->string('pro_size')->nullable();
            $table->string('pro_quality')->nullable();            
            $table->string('SKUs')->nullable();
            $table->unsignedTinyInteger('is_delete')->default(0)->comment('1=Delete');
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive');
            $table->string('primary_image')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_4')->nullable();
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
        Schema::dropIfExists('products');
    }
}
