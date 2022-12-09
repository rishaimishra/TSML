<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScPriceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_price_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mat_id');
            $table->string('mat_code');
            $table->string('cnty');
            $table->string('des');
            $table->string('amt');
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
        Schema::dropIfExists('sc_price_details');
    }
}
