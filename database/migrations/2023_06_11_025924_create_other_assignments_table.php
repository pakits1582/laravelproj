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
        Schema::create('other_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
            $table->unsignedBigInteger('instructor_id')->index();
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->string('assignment')->nullable();
            $table->float('units')->nullable();
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
        Schema::dropIfExists('other_assignments');
    }
};
