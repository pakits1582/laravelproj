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
        Schema::create('studentadjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->index();
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
            $table->tinyInteger('type')->default('0')->nullable();
            $table->string('particular');
            $table->decimal('amount', 9, 2)->default('0')->nullable();
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
        Schema::dropIfExists('studentadjustments');
    }
};
