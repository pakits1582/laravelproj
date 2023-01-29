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
        Schema::create('fees_setups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
            $table->unsignedBigInteger('educational_level_id')->index()->nullable();
            $table->foreign('educational_level_id')->references('id')->on('educational_levels')->onDelete('cascade');
            $table->unsignedBigInteger('college_id')->index()->nullable();
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');
            $table->unsignedBigInteger('program_id')->index()->nullable();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->integer('year_level')->nullable();
            $table->unsignedBigInteger('subject_id')->index()->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->boolean('new')->default(false);
            $table->boolean('old')->default(false);
            $table->boolean('transferee')->default(false);
            $table->boolean('cross_enrollee')->default(false);
            $table->boolean('foreigner')->default(false);
            $table->boolean('returnee')->default(false);
            $table->boolean('professional')->default(false);
            $table->tinyInteger('sex')->nullable();
            $table->unsignedBigInteger('fee_id')->index()->nullable();
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->decimal('rate', 9, 2)->default('0.00');
            $table->enum('payment_scheme', [1,2,3]);
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
        Schema::dropIfExists('fees_setups');
    }
};
