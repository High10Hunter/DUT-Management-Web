<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddBirthdayColumnToStudentsAndLecturersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('students', 'birthday')) {
            Schema::table('students', function (Blueprint $table) {
                $table->date('birthday')->after('password');
            });
        }
        if (!Schema::hasColumn('lecturers', 'birthday')) {
            Schema::table('lecturers', function (Blueprint $table) {
                $table->date('birthday')->after('password');
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
        Schema::table('students_and_lecturers_tables', function (Blueprint $table) {
            //
        });
    }
}
