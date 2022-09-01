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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->float('units')->nullable();
            $table->float('tfunits')->nullable();
            $table->float('loadunits')->nullable();
            $table->float('lecunits')->nullable();
            $table->float('labunits')->nullable();
            $table->float('hours')->nullable();
            $table->unsignedBigInteger('educational_level')->nullable();
            $table->foreign('educational_level')->references('id')->on('educational_levels');
            $table->unsignedBigInteger('college')->nullable();
            $table->foreign('college')->references('id')->on('colleges');
            $table->boolean('professional')->default(0);
            $table->boolean('laboratory')->default(0);
            $table->boolean('gwa')->default(0);
            $table->boolean('nograde')->default(0);
            $table->boolean('notuition')->default(0);
            $table->boolean('exclusive')->default(0);
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
        Schema::dropIfExists('subjects');
    }
};
