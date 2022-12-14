<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScmaterialDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scmaterial_description', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('con_mat_id')->nullable();
            $table->string('mat_code')->nullable();
            $table->string('pcode')->nullable();
            $table->string('rfq_no')->nullable();
            $table->string('total')->nullable();
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
        Schema::dropIfExists('scmaterial_description');
    }
}
