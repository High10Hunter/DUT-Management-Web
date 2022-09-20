<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('gender')->default(true);
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->smallInteger('role')->default(1);
            $table->boolean('status')->default(true);
            $table->foreignId('class_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('students');
    }
}
