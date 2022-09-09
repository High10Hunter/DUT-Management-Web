<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('username');
            $table->string('password');
            $table->boolean('gender')->default(true);
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->smallInteger('role')->default(1);
            $table->boolean('status')->default(true);
            $table->foreignId('faculty_id')->constrained()->nullable();
            $table->foreignId('class_id')->constrained()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
