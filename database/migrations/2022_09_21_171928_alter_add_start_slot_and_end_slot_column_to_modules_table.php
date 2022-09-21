<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddStartSlotAndEndSlotColumnToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('modules', 'start_slot')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->smallInteger('start_slot');
            });
        }
        if (!Schema::hasColumn('modules', 'end_slot')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->smallInteger('end_slot');
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
