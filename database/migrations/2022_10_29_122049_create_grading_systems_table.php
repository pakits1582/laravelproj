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
        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_level_id')->index();
            $table->foreign('educational_level_id')->references('id')->on('educational_levels')->onDelete('cascade');
            $table->string('code');
            $table->string('value');
            $table->unsignedBigInteger('remark_id')->index()->nullable();
            $table->foreign('remark_id')->references('id')->on('remarks')->nullOnDelete();
            $table->unsignedBigInteger('period_id')->index()->nullable();
            $table->foreign('period_id')->references('id')->on('periods')->nullOnDelete();
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
        Schema::dropIfExists('grading_systems');
    }
};
