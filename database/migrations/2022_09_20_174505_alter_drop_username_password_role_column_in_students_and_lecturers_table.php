<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDropUsernamePasswordRoleColumnInStudentsAndLecturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('students', 'username')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
        if (Schema::hasColumn('students', 'password')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
        if (Schema::hasColumn('students', 'role')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
        if (Schema::hasColumn('lecturers', 'username')) {
            Schema::table('lecturers', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
        if (Schema::hasColumn('lecturers', 'password')) {
            Schema::table('lecturers', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
        if (Schema::hasColumn('lecturers', 'role')) {
            Schema::table('lecturers', function (Blueprint $table) {
                $table->dropColumn('role');
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
        Schema::table('students_and_lecturers', function (Blueprint $table) {
            //
        });
    }
}
