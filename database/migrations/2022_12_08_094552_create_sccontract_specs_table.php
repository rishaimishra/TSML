<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSccontractSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sccontract_specs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mat_id');
            $table->string('comp')->nullable();
            $table->string('max')->nullable();
            $table->string('min')->nullable();
            $table->string('permissible')->nullable();
            $table->string('uom')->nullable();
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
        Schema::dropIfExists('sccontract_specs');
    }
}
