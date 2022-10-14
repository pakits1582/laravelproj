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
        Schema::create('section_monitorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id')->index();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->integer('minimum_enrollees')->nullable()->default(0);
            $table->integer('allowed_units')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0);
            $table->unsignedBigInteger('period_id')->index();
            $table->foreign('period_id')->references('id')->on('periods');
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
        Schema::dropIfExists('section_monitorings');
    }
};
