<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyProductionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_production_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('plant');
            $table->string('cat_id');
            $table->string('sub_cat_id');
            $table->string('size');
            $table->string('open_stk');
            $table->string('mnthly_prod');
            $table->decimal('export','9','2');
            $table->decimal('offline','9','2');
            $table->decimal('sap_order','9','2');
            $table->decimal('fg_sap','9','2');
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
        Schema::dropIfExists('monthly_production_plans');
    }
}
