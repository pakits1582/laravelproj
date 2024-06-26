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
        Schema::create('student_contact_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
           
            $table->string('current_region', 100)->nullable();
            $table->string('current_province', 100)->nullable();
            $table->string('current_municipality', 100)->nullable();
            $table->string('current_barangay', 100)->nullable();
            $table->string('current_address')->nullable();
            $table->string('current_zipcode', 20)->nullable();

            $table->string('permanent_region', 100)->nullable();
            $table->string('permanent_province', 100)->nullable();
            $table->string('permanent_municipality', 100)->nullable();
            $table->string('permanent_barangay', 100)->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('permanent_zipcode', 20)->nullable();

            $table->string('email', 150)->nullable();
            $table->string('mobileno', 20)->nullable();
            $table->string('telno', 20)->nullable();

            $table->string('notify_person')->nullable();
            $table->string('notify_contactno', 20)->nullable();
            $table->string('notify_address')->nullable();

            $table->string('contact_email', 150)->nullable();
            $table->string('contact_no', 20)->nullable();

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
        Schema::dropIfExists('student_profiles');
    }
};
