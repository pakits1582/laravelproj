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
        Schema::table('students', function (Blueprint $table) {
            $table->string('picture', 150)->nullable()->after('user_id');
            $table->string('report_card')->nullable();
            $table->string('als_certificate')->nullable();
            $table->tinyInteger('classification')->default(0);
            $table->string('application_no')->index()->nullable();
            $table->tinyInteger('application_status')->default(0);
            $table->tinyInteger('admission_status')->default(0);
            $table->date('admission_date')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('entry_data')->nullable();
            $table->unsignedBigInteger('entry_period')->index();
            $table->foreign('entry_period')->references('id')->on('periods')->nullOnDelete();

            $table->unsignedBigInteger('assessed_by')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('assessed_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('picture');
            $table->dropColumn('report_card');
            $table->dropColumn('als_certificate');
            $table->dropColumn('classification');
            $table->dropColumn('application_no');
            $table->dropColumn('application_status');
            $table->dropColumn('admission_status');
            $table->dropColumn('admission_date');
            $table->dropColumn('entry_date');
            $table->dropColumn('entry_data');
            $table->dropColumn('entry_period');
        });
    }
};
