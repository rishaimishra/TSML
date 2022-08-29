<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categorys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cat_id'); 
            $table->string('sub_cat_name')->nullable();
            $table->string('sub_cat_dese')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive');
            $table->foreign('cat_id')->references('id')->on('categorys')->onDelete('cascade');
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
        Schema::dropIfExists('sub_categorys');
    }
}
