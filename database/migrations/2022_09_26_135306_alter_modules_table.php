<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('modules', 'schedule')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->json('schedule')->change();
            });
        }

        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->smallInteger('lessons')->after('begin_date');
        });

        Schema::table('modules', function (Blueprint $table) {
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
        //
    }
}
