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
        Schema::create('studentledger_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studentledger_id')->index();
            $table->foreign('studentledger_id')->references('id')->on('studentledgers')->onDelete('cascade');
            $table->unsignedBigInteger('fee_id')->index();
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->decimal('amount', 9, 2)->default('0')->nullable();
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
        Schema::dropIfExists('studentledger_details');
    }
};
