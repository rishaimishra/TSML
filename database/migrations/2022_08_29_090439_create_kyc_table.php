<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->string('gst_number');
            $table->string('linked_address')->nullable();
            $table->string('pan_number')->unique();
            $table->string('company_name')->nullable();
            $table->string('business_nature')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('kyc');
    }
}
