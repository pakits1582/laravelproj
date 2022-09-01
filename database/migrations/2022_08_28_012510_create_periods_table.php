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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->unsignedBigInteger('term')->nullable();
            $table->foreign('term')->references('id')->on('terms');
            $table->tinyInteger('year')->index();
            $table->date('class_start')->nullable();
            $table->date('class_end')->nullable();
            $table->date('class_ext')->nullable();
            $table->date('enroll_start')->nullable();
            $table->date('enroll_end')->nullable();
            $table->date('enroll_ext')->nullable();
            $table->date('adddrop_start')->nullable();
            $table->date('adddrop_end')->nullable();
            $table->date('adddrop_ext')->nullable();
            $table->string('idmask');
            $table->tinyInteger('source')->default(1);
            $table->boolean('display')->default(0);
            $table->tinyInteger('priority_lvl');
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
        Schema::dropIfExists('periods');
    }
};
