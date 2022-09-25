<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('periods', 'class_id')) {
            Schema::table('periods', function (Blueprint $table) {
                $table->dropForeign('periods_class_id_foreign');
                $table->dropColumn('class_id');
            });
        }
        if (Schema::hasColumn('periods', 'subject_id')) {
            Schema::table('periods', function (Blueprint $table) {
                $table->dropForeign('periods_subject_id_foreign');
                $table->dropColumn('subject_id');
            });
        }
        // if (Schema::hasColumn('periods', 'date')) {
        //     Schema::table('periods', function (Blueprint $table) {
        //         $table->dropIndex('periods_class_id_subject_id_date_unique');
        //     });
        // }

        if (!Schema::hasColumn('periods', 'module_id')) {
            Schema::table('periods', function (Blueprint $table) {
                $table->unsignedBigInteger('module_id')->after('id');
                $table->foreign('module_id')->references('id')->on('modules');
            });
        }

        Schema::table('periods', function (Blueprint $table) {
            $table->unique([
                'module_id',
                'date'
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
