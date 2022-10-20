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
            $table->foreignId('module_id')->constrained();
            $table->date('date');
            $table->smallInteger('type')->default(0);
            $table->smallInteger('start_slot');
            $table->unsignedBigInteger('proctor_id');
            $table->foreign('proctor_id')->references('id')->on('lecturers');
            $table->unique([
                'module_id',
                'date',
                'type'
            ]);
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
