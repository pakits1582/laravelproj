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
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->Integer('receipt_no')->index()->nullable();
            $table->foreign('receipt_no')->references('receipt_no')->on('receipts')->cascadeOnDelete();
            $table->integer('source_id')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('fee_id')->index()->nullable();
            $table->foreign('fee_id')->references('id')->on('fees')->nullOnDelete();
            $table->decimal('amount', 9, 2)->default(0)->nullable();
            $table->string('payor_name');
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
        Schema::dropIfExists('receipt_details');
    }
};
