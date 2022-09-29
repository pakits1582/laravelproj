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
        Schema::create('curriculum_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curriculum_id')->nullable()->index();
            $table->foreign('curriculum_id')->references('id')->on('curricula');
            $table->unsignedBigInteger('subject_id')->nullable()->index();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('term_id')->nullable()->index();
            $table->foreign('term_id')->references('id')->on('terms');
            $table->integer('year_level')->default(0)->nullable()->index();
            $table->string('quota')->nullable();
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
        Schema::dropIfExists('curriculum_subjects');
    }
};
