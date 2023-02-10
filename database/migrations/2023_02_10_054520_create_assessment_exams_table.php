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
        Schema::create('assessment_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id')->index();
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->decimal('amount', 9, 2)->default('0.00')->nullable();
            $table->decimal('downpayment', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam1', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam2', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam3', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam4', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam5', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam6', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam7', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam8', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam9', 9, 2)->default('0.00')->nullable();
            $table->decimal('exam10', 9, 2)->default('0.00')->nullable();

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
        Schema::dropIfExists('assessment_exams');
    }
};
