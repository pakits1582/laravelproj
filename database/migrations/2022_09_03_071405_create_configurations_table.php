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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('contactno')->nullable();
            $table->string('address')->nullable();
            $table->string('accronym')->nullable();
            $table->string('president')->nullable();
            $table->string('pres_sig')->nullable();
            $table->string('pres_initials')->nullable();
            $table->string('registrar')->nullable();
            $table->string('reg_sig')->nullable();
            $table->string('reg_initials')->nullable();
            $table->string('treasurer')->nullable();
            $table->string('tres_sig')->nullable();
            $table->string('tres_initials')->nullable();
            $table->decimal('balanceallowed', 11,2)->nullable();
            $table->tinyInteger('due')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('current_period')->nullable();
            $table->foreign('current_period')->references('id')->on('periods');
            $table->unsignedBigInteger('application_period')->nullable();
            $table->foreign('application_period')->references('id')->on('periods');
            $table->date('datefrom')->nullable();
            $table->date('dateto')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->text('announcement')->nullable();
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
        Schema::dropIfExists('configurations');
    }
};
