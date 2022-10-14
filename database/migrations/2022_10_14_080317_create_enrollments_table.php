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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods');
            $table->unsignedBigInteger('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('program_id')->index();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->unsignedBigInteger('curriculum_id')->index()->nullable();
            $table->foreign('curriculum_id')->references('id')->on('curricula');
            $table->unsignedBigInteger('section_id')->index()->nullable();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->tinyInteger('year_level')->nullable()->default(0);
            $table->boolean('new')->default(false);
            $table->boolean('old')->default(false);
            $table->boolean('transferee')->default(false);
            $table->boolean('foreigner')->default(false);
            $table->boolean('returnee')->default(false);
            $table->boolean('acctok')->default(false);
            $table->boolean('assessed')->default(false);
            $table->boolean('validated')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->boolean('withdrawn')->default(false);
            $table->tinyInteger('enrolled_units')->nullable()->default(0);
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('enrollments');
    }
};
