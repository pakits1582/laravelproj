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
        Schema::create('internal_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->index();
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->unsignedBigInteger('class_id')->index();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->unsignedBigInteger('grading_system_id')->index()->nullable();
            $table->foreign('grading_system_id')->references('id')->on('grading_systems')->onDelete('cascade');
            $table->unsignedBigInteger('completion_grade')->index()->nullable();
            $table->foreign('completion_grade')->references('id')->on('grading_systems')->onDelete('cascade');
            $table->float('units')->nullable();
            $table->boolean('final')->default(false);
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('internal_grades');
    }
};
