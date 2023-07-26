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
        Schema::create('student_personal_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
           
            $table->string('civil_status', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('nationality', 150)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('religion_specify', 150)->nullable();

            $table->boolean('baptism')->default(0);
            $table->boolean('communion')->default(0);
            $table->boolean('confirmation')->default(0);

            $table->boolean('father_alive')->default(0);
            $table->string('father_name')->nullable();
            $table->string('father_address')->nullable();
            $table->string('father_contactno', 30)->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_employer')->nullable();
            $table->string('father_employer_address')->nullable();

            $table->boolean('mother_alive')->default(0);
            $table->string('mother_name')->nullable();
            $table->string('mother_address')->nullable();
            $table->string('mother_contactno', 30)->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_employer')->nullable();
            $table->string('mother_employer_address')->nullable();

            $table->boolean('guardian_alive')->default(0);
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship', 100)->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_contactno', 30)->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_employer')->nullable();
            $table->string('guardian_employer_address')->nullable();

            $table->string('occupation')->nullable();
            $table->tinyInteger('occupation_years')->default(0);
            $table->string('employer')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('employer_contact')->nullable();

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
        Schema::dropIfExists('user_personal_informations');
    }
};
