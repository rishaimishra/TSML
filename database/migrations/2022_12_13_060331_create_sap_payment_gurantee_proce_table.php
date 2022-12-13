<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapPaymentGuranteeProceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sap_payment_gurantee_proce', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->string('pay_gurantee_code')->nullable();
            $table->string('pay_gurantee_dec')->nullable();
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
        Schema::dropIfExists('sap_payment_gurantee_proce');
    }
}
