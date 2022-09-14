<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddCourseIdAndNumberOfCreditsColumnToMajorSubjectTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('major_subject', 'course_id')) {
            Schema::table('major_subject', function (Blueprint $table) {
                $table->foreignId('course_id')->constrained();
            });
        }
        if (!Schema::hasColumn('major_subject', 'number_of_credits')) {
            Schema::table('major_subject', function (Blueprint $table) {
                $table->smallInteger('number_of_credits');
            });
        }
        // Schema::table('major_subject', function (Blueprint $table) {
        //     $table->primary('course_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('major_subject', function (Blueprint $table) {
            //
        });
    }
}
