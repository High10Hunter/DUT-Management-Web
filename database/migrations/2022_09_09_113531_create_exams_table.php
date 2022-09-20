<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->date('date');
            $table->smallInteger('type')->default(0);
            $table->unsignedBigInteger('proctor_id');
            $table->foreign('proctor_id')->references('id')->on('lecturers');
            $table->unsignedBigInteger('examiner_id');
            $table->foreign('examiner_id')->references('id')->on('lecturers');
            $table->unique([
                'class_id',
                'subject_id',
                'date',
                'type'
            ]);
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
        Schema::dropIfExists('exams');
    }
}
