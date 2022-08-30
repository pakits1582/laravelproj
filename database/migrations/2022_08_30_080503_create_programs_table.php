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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('educational_level')->nullable();
            $table->foreign('educational_level')->references('id')->on('educational_levels');
            $table->unsignedBigInteger('college')->nullable();
            $table->foreign('college')->references('id')->on('colleges');
            $table->unsignedBigInteger('head')->nullable();
            $table->foreign('head')->references('id')->on('instructors');
            $table->integer('years')->default(0);
            $table->tinyInteger('source')->default(1);
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('programs');
    }
};
