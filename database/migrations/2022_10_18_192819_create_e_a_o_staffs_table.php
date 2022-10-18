
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEAOStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eao_staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthday');
            $table->boolean('gender')->default(true);
            $table->string('email');
            $table->string('phone_number');
            $table->smallInteger('role')->default(1);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_a_o_staffs');
    }
}
