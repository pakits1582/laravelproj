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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods');
            $table->unsignedBigInteger('section_id')->index();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('curriculum_subject_id')->nullable()->index();
            $table->foreign('curriculum_subject_id')->references('id')->on('curriculum_subjects');
            $table->float('units')->nullable();
            $table->float('tfunits')->nullable();
            $table->float('loadunits')->nullable();
            $table->float('lecunits')->nullable();
            $table->float('labunits')->nullable();
            $table->float('hours')->nullable();
            $table->unsignedBigInteger('instructor_id')->nullable()->index();
            $table->foreign('instructor_id')->references('id')->on('instructors');
            $table->unsignedBigInteger('schedule_id')->nullable()->index();
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->integer('slots')->nullable();
            $table->boolean('tutorial')->default(false);
            $table->boolean('dissolved')->default(false);
            $table->boolean('f2f')->default(false);
            $table->boolean('merge')->default(false);
            $table->boolean('ismother')->default(false);
            $table->boolean('isprof')->default(false);
            $table->boolean('evaluation')->default(false);
            $table->unsignedBigInteger('evaluated_by')->nullable()->index();
            $table->foreign('evaluated_by')->references('id')->on('instructors');
            $table->integer('evaluation_status')->nullable()->default(0);
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
        Schema::dropIfExists('classes');
    }
};
