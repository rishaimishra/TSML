<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('desc');
            $table->string('desc_no');
            $table->string('sender_ids');
            $table->string('user_id');
            $table->string('url_type')->comment('R=Rfq|P=PO');
            $table->integer('status');
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
        Schema::dropIfExists('sales_notifications');
    }
}
