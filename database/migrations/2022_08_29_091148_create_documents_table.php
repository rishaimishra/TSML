<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->string('address_proof');
            $table->string('cancel_cheque');
            $table->string('pan_card');
            $table->string('gst_certificate');
            $table->string('email')->unique();
            $table->string('turnover_declare')->nullable();
            $table->string('itr_last_yr')->nullable();
            $table->string('form_d')->nullable();
            $table->string('registration_certified')->nullable();
            $table->string('tcs')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
