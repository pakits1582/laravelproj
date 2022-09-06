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
        Schema::create('configuration_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_level')->nullable()->index();
            $table->foreign('educational_level')->references('id')->on('educational_levels');
            $table->unsignedBigInteger('college')->nullable()->index();
            $table->foreign('college')->references('id')->on('colleges');
            $table->tinyInteger('year')->default(0);
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->unsignedBigInteger('period')->nullable()->index();
            $table->foreign('period')->references('id')->on('periods');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('configuration_schedules');
    }
};
