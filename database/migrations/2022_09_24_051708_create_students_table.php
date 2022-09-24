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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('last_name')->index();
            $table->string('first_name')->index();
            $table->string('middle_name')->nullable();
            $table->string('name_suffix')->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->unsignedBigInteger('program_id')->nullable()->index();
            $table->foreign('program_id')->references('id')->on('users');
            $table->tinyInteger('year_level')->nullable();
            $table->unsignedBigInteger('curriculum_id')->nullable()->index();
            $table->foreign('curriculum_id')->references('id')->on('curricula');
            $table->tinyInteger('academic_status')->nullable();
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
        Schema::dropIfExists('students');
    }
};
