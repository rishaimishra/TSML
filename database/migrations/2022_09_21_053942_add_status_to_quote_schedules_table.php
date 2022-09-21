<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToQuoteSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_schedules', function (Blueprint $table) {
            $table->integer('quote_status')->comment('0=ongoing,1=approved,2=rejected')->default('0')->after('valid_till');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_schedules', function (Blueprint $table) {
            //
        });
    }
}
