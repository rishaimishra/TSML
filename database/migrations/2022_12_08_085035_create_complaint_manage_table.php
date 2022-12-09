<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_manage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kam_id');
            $table->unsignedBigInteger('complain_id');
            $table->string('po_no')->nullable();
            $table->unsignedBigInteger('depa_id')->nullable();
            $table->string('r_mail')->nullable();
            $table->string('ka_remarks')->nullable();  
            $table->unsignedTinyInteger('is_mail_resiv')->default(0)->comment('0=No|1=Yes'); 
            $table->string('interim_report')->nullable();
            $table->string('final_report')->nullable();
            $table->string('capa')->nullable();
            $table->string('financial_set_repo')->nullable(); 
            $table->string('sales_approval')->nullable();
            $table->string('marketing_head_approval')->nullable();
            $table->string('sr_gm_approval')->nullable();
            $table->string('financial_approval_op')->nullable(); 
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
        Schema::dropIfExists('complaint_manage');
    }
}
