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
        Schema::create('prerequisites', function (Blueprint $table) {
            $table->engine = "InnoDB"; 
            $table->id();
            $table->unsignedBigInteger('curriculum_subject_id')->nullable()->index();
            $table->foreign('curriculum_subject_id')->references('id')->on('curriculum_subjects');
            $table->unsignedBigInteger('prerequisite')->nullable()->index();
            $table->foreign('prerequisite')->references('id')->on('curriculum_subjects');
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
        Schema::dropIfExists('prerequisites');
    }
};
