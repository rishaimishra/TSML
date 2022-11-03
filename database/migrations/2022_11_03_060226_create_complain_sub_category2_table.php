<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainSubCategory2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complain_sub_categorys2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('com_cate_id');
            $table->unsignedBigInteger('com_sub_cate_id');
            $table->string('com_sub_cate2_name')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive');
            $table->foreign('com_cate_id')->references('id')->on('complain_categorys')->onDelete('cascade');
            $table->foreign('com_sub_cate_id')->references('id')->on('complain_sub_categorys')->onDelete('cascade');
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
        Schema::dropIfExists('complain_sub_categorys2');
    }
}
