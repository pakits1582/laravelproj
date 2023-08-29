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
        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_level_id')->index();
            $table->foreign('educational_level_id')->references('id')->on('educational_levels');
            $table->unsignedBigInteger('program_id')->index()->nullable();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->integer('classification')->nullable();
            $table->string('description');
            $table->boolean('display')->default(0);
            $table->boolean('is_required')->default(0);
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
        Schema::dropIfExists('adminssion_documents');
    }
};
