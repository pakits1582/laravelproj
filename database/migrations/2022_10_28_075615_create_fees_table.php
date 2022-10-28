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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->unsignedBigInteger('fee_type_id')->nullable();
            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
            $table->integer('colindex')->nullable()->default(0);
            $table->decimal('default_value', 9, 2)->default('0.00');
            $table->boolean('iscompound')->default(false);
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
        Schema::dropIfExists('fees');
    }
};
