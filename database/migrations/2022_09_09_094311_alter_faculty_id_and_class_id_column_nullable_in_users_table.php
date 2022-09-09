<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFacultyIdAndClassIdColumnNullableInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'faculty_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('faculty_id')->nullable()->change();
            });
        }
        if (Schema::hasColumn('users', 'class_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('class_id')->nullable()->change();
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
