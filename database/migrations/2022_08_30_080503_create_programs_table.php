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
            $table->string('code')->index();
            $table->string('name')->index();
            $table->unsignedBigInteger('educational_level_id')->nullable();
            $table->foreign('educational_level_id')->references('id')->on('educational_levels');
            $table->unsignedBigInteger('college_id')->nullable();
            $table->foreign('college_id')->references('id')->on('colleges');
            $table->unsignedBigInteger('head')->nullable();
            $table->foreign('head')->references('id')->on('instructors');
            $table->integer('years')->default(0);
            $table->tinyInteger('source')->default(1);
            $table->boolean('active')->default(0);
            $table->string('type')->index();
            $table->boolean('display')->default(0);
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
