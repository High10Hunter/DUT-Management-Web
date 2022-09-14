<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndRecreateMajorSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('major_subject');
        if (!Schema::hasTable('major_subject')) {
            Schema::create('major_subject', function (Blueprint $table) {
                $table->foreignId('major_id')->constrained();
                $table->foreignId('subject_id')->constrained();
                $table->foreignId('course_id')->constrained();
                $table->smallInteger('number_of_credits');
                $table->primary([
                    'major_id',
                    'subject_id',
                    'course_id',
                ]);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('major_subject');
    }
}
