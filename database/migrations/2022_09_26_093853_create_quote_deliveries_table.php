<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quote_sche_no')->comment('quote_schedules.schedule_no');
            $table->string('qty');
            $table->date('to_date');
            $table->date('from_date');
            $table->softDeletes();
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
        Schema::dropIfExists('quote_deliveries');
    }
}
