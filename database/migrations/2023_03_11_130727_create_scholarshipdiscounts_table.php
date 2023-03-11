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
        Schema::create('scholarshipdiscounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('description');
            $table->decimal('tution', 9, 2)->default('0')->nullable();
            $table->enum('tuition_type', ['rate', 'fixed'])->default('rate');
            $table->decimal('miscellaneous', 9, 2)->default('0')->nullable();
            $table->enum('miscellaneous_type', ['rate', 'fixed'])->default('rate');
            $table->decimal('othermisc', 9, 2)->default('0')->nullable();
            $table->enum('othermisc_type', ['rate', 'fixed'])->default('rate');
            $table->decimal('laboratory', 9, 2)->default('0')->nullable();
            $table->enum('laboratory_type', ['rate', 'fixed'])->default('rate');
            $table->decimal('totalassessment', 9, 2)->default('0')->nullable();
            $table->enum('totalassessment_type', ['rate', 'fixed'])->default('rate');
            $table->enum('type', [1, 2])->default(1)->index();
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
        Schema::dropIfExists('scholarshipdiscounts');
    }
};
