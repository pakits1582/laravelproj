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
        Schema::create('overall_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_evaluation_id')->index();
            $table->foreign('faculty_evaluation_id')->references('id')->on('faculty_evaluations')->onDelete('cascade');
            $table->enum('answer', [1,2,3,4]);
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
        Schema::dropIfExists('overall_rates');
    }
};
