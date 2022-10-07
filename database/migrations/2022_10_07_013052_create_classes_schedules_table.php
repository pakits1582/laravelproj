<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classes_id')->index();
            $table->foreign('classes_id')->references('id')->on('classes');
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
            $table->string('day')->nullable();
            $table->unsignedBigInteger('room_id')->index();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->unsignedBigInteger('schedule_id')->index();
            $table->foreign('schedule_id')->references('id')->on('schedules');
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
        Schema::dropIfExists('classes_schedules');
    }
};
