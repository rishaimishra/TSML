<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyProdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plant');
            $table->string('category');
            $table->string('subcategory');
            $table->string('met_group');
            $table->string('met_no');
            $table->string('grade_code');
            $table->string('size');
            $table->string('qty');
            $table->date('start');
            $table->date('end');
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
        Schema::dropIfExists('daily_prod');
    }
}
