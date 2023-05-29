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
        Schema::create('issueing_offices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('soresolutions', function (Blueprint $table) {
            $table->id();
            $table->string('conjunction')->nullable();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('grade_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->index();
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->unsignedBigInteger('school_id')->index()->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->nullOnDelete();
            $table->unsignedBigInteger('program_id')->index()->nullable();
            $table->foreign('program_id')->references('id')->on('programs')->nullOnDelete();
            $table->string('thesis_title')->nullable();
            $table->date('graduation_date')->nullable();
            $table->integer('graduation_award')->nullable()->default(0);
            $table->unsignedBigInteger('soresolution_id')->index()->nullable();
            $table->foreign('soresolution_id')->references('id')->on('soresolutions')->nullOnDelete();
            $table->string('soresolution_no')->nullable();
            $table->string('soresolution_series')->nullable();
            $table->unsignedBigInteger('issueing_office_id')->index()->nullable();
            $table->foreign('issueing_office_id')->references('id')->on('issueing_offices')->nullOnDelete();
            $table->date('issued_date')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('grade_informations');
    }
};
