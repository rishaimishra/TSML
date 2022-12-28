<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plant_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('desc');
            $table->string('desc_no');
            $table->string('sender_id');
            $table->string('plant_id');
            $table->string('url_type')->nullable();
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
        Schema::dropIfExists('plant_notifications');
    }
}
