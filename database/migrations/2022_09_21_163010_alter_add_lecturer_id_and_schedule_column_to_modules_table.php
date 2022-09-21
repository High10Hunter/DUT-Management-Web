<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddLecturerIdAndScheduleColumnToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('modules', 'lecturer_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->foreignId('lecturer_id')->constrained()->after('subject_id');
            });
        }
        if (!Schema::hasColumn('modules', 'schedule')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('schedule')->after('lecturer_id');
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
        Schema::table('modules', function (Blueprint $table) {
            //
        });
    }
}
