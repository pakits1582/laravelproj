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
        Schema::create('student_academic_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
            $table->boolean('is_alsfinisher')->default(0);
            $table->string('elem_school')->nullable();
            $table->string('elem_address')->nullable();
            $table->string('elem_period', 50)->nullable();

            $table->string('jhs_school')->nullable();
            $table->string('jhs_address')->nullable();
            $table->string('jhs_period', 50)->nullable();

            $table->string('shs_school')->nullable();
            $table->string('shs_address')->nullable();
            $table->string('shs_period', 50)->nullable();
            $table->string('shs_strand')->nullable();
            $table->string('shs_techvoc_specify')->nullable();

            $table->string('college_school')->nullable();
            $table->string('college_program')->nullable();
            $table->string('college_address')->nullable();
            $table->string('college_period', 50)->nullable();

            $table->string('graduate_school')->nullable();
            $table->string('graduate_program')->nullable();
            $table->string('graduate_address')->nullable();
            $table->string('graduate_period', 50)->nullable();

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
        Schema::dropIfExists('student_academic_informations');
    }
};
