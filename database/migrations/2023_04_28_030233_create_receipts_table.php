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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods');
            $table->integer('receipt_no')->unique()->index();
            $table->unsignedBigInteger('fee_id')->index()->nullable();
            $table->foreign('fee_id')->references('id')->on('fees')->nullOnDelete();
            $table->unsignedBigInteger('student_id')->index()->nullable();
            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
            $table->unsignedBigInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->nullOnDelete();
            $table->date('deposit_date')->nullable();
            $table->dateTime('receipt_date')->index();
            $table->decimal('amount', 9, 2)->default(0)->nullable();
            $table->boolean('deffered')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->string('cancel_remark')->nullable();
            $table->boolean('in_assess')->default(false);
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
        Schema::dropIfExists('receipts');
    }
};
