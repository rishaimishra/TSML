<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OptNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opt_notification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('desc');
            $table->string('desc_no');
            $table->string('sender_ids'); 
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
        Schema::dropIfExists('opt_notification');
    }
}
