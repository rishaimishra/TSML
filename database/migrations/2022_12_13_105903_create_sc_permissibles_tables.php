<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScPermissiblesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sc_permissibles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mat_id')->nullable();
            $table->string('mat_code')->nullable();
            $table->string('perm_percent')->nullable();
            $table->string('umo')->nullable();
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
        Schema::dropIfExists('sc_permissibles_tables');
    }
}
