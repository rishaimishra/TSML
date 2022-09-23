<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pickup_from')->nullable(); 
            $table->string('location')->nullable();
            $table->string('freight_charges')->nullable(); 
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Active|2=Inactive|3=Delete');
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
        Schema::dropIfExists('freights');
    }
}
