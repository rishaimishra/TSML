<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSccontractMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sccontract_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contarct_id');
            $table->string('mat_code');
            $table->string('pcode');
            $table->string('rfq_no');
            $table->string('total');
            $table->string('incoterms');
            $table->string('pay_terms');
            $table->string('freight');
            $table->string('cus_grp');
            $table->string('fr_ind');
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
        Schema::dropIfExists('sccontract_materials');
    }
}
