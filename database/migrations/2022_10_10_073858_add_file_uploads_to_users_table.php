<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileUploadsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('pan_dt')->nullable()->after('tcs');
            $table->date('gst_dt')->nullable()->after('pan_dt');
            $table->date('formD_dt')->nullable()->after('gst_dt');
            $table->date('tcs_dt')->nullable()->after('formD_dt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
