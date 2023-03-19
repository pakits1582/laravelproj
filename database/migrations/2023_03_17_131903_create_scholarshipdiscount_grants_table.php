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
        Schema::create('scholarshipdiscount_grants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->index();
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
            $table->unsignedBigInteger('scholarshipdiscount_id')->index();
            $table->foreign('scholarshipdiscount_id')->references('id')->on('scholarshipdiscounts');
            $table->decimal('tuition', 9, 2)->default('0')->nullable();
            $table->decimal('miscellaneous', 9, 2)->default('0')->nullable();
            $table->decimal('othermisc', 9, 2)->default('0')->nullable();
            $table->decimal('laboratory', 9, 2)->default('0')->nullable();
            $table->decimal('totalassessment', 9, 2)->default('0')->nullable();
            $table->decimal('totaldeduction', 9, 2)->default('0')->nullable();
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
        Schema::dropIfExists('scholarshipdiscount_grants');
    }
};
