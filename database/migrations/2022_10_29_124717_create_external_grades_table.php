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
        Schema::create('external_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->index()->nullable();
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->string('subject_code');
            $table->string('subject_description')->nullable();
            $table->string('grade')->nullable();
            $table->string('completion_grade')->nullable();
            $table->string('equivalent_grade')->nullable();
            $table->string('units')->nullable();
            $table->unsignedBigInteger('remark_id')->index()->nullable();
            $table->foreign('remark_id')->references('id')->on('remarks')->nullOnDelete();
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
        Schema::dropIfExists('external_grades');
    }
};
