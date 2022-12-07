<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('value');
            $table->string('mat_code');
            $table->string('plant');
            $table->string('schedule');
            $table->string('rfq_no');
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
        Schema::dropIfExists('sc_transactions');
    }
}
