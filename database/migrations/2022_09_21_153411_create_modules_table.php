<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('lecturer_id')->constrained();
            $table->json('schedule');
            $table->smallInteger('start_slot');
            $table->smallInteger('end_slot');
            $table->date('begin_date');
            $table->smallInteger('lessons');
            $table->boolean('status');
            $table->unique([
                'name',
                'lecturer_id',
                'start_slot',
                'end_slot',
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
        Schema::dropIfExists('modules');
    }
}
