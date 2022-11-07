<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complain_main', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('com_cate_id');
            $table->unsignedBigInteger('com_sub_cate_id');
            $table->unsignedBigInteger('com_sub_cate_2id');
            $table->unsignedBigInteger('com_sub_cate_3id');
            $table->unsignedBigInteger('com_sub_cate_3id');
            $table->unsignedBigInteger('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->string('file')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedTinyInteger('closed_status')->default(1)->comment('1=Open|2=Closed');
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
        Schema::dropIfExists('complain_main');
    }
}
