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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_category_id')->index()->nullable();
            $table->foreign('question_category_id')->references('id')->on('question_categories')->onDelete('cascade');
            $table->unsignedBigInteger('question_subcategory_id')->index()->nullable();
            $table->foreign('question_subcategory_id')->references('id')->on('question_subcategories')->onDelete('cascade');
            $table->unsignedBigInteger('question_group_id')->index()->nullable();
            $table->foreign('question_group_id')->references('id')->on('question_groups')->onDelete('cascade');
            $table->text('question');
            $table->unsignedBigInteger('educational_level_id')->index()->nullable();
            $table->foreign('educational_level_id')->references('id')->on('educational_levels')->onDelete('cascade');
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
        Schema::dropIfExists('questions');
    }
};
