<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_order_tasks', function (Blueprint $table) {
            $table->text('note')->after('employee_fee_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_order_tasks', function (Blueprint $table) {
            //
            $table->dropColumn('note');
        });
    }
};
