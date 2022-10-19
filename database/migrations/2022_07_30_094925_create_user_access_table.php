<?php

use App\Models\Useraccess;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('access', 150)->index();
            $table->string('title', 150)->index();
            $table->string('category', 100);
            $table->boolean('read_only')->default(1);
            $table->boolean('write_only')->default(1);
            $table->timestamps();
        });

        Useraccess::create([
            'user_id' => 1,
            'access' => 'users',
            'title' => 'User Accounts',
            'category' => 'General',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_access');
    }
};
