<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDropClassAndFacultyColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'class_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_class_id_foreign');
                $table->dropColumn('class_id');
            });
        }
        if (Schema::hasColumn('users', 'faculty_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_faculty_id_foreign');
                $table->dropColumn('faculty_id');
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
