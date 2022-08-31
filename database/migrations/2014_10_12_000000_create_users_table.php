<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gstin')->nullable();
            $table->string('org_pan')->nullable();
            $table->string('org_name')->nullable();
            $table->string('org_address')->nullable();
            $table->string('pref_product')->nullable();
            $table->string('pref_product_size')->nullable();
            $table->string('user_type')->nullable();
            $table->string('company_gst');
            $table->string('company_linked_address')->nullable();
            $table->string('company_pan')->unique();
            $table->string('company_name')->nullable();
            $table->string('business_nature')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('addressone')->nullable();
            $table->string('addresstwo')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('address_type')->nullable();
            $table->string('address_proof_file')->nullable();
            $table->string('cancel_cheque_file')->nullable();
            $table->string('pan_card_file')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('turnover_declare')->nullable();
            $table->string('itr_last_yr')->nullable();
            $table->string('form_d')->nullable();
            $table->string('registration_certificate')->nullable();
            $table->string('tcs')->nullable();
            $table->string('user_status')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
