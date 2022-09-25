<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddStudyTimeAndStatusColumnToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('modules', 'begin_date')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->date('begin_date');
            });
        }
        if (!Schema::hasColumn('modules', 'end_date')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->date('end_date');
            });
        }
        if (!Schema::hasColumn('modules', 'status')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->boolean('status');
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
