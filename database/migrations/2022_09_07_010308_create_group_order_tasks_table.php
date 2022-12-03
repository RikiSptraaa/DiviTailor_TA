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
        Schema::create('group_order_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_order_id')->constrained('group_orders')->cascadeOnDelete();
            $table->foreignId('handler_id')->constrained('employees')->cascadeOnDelete();
            $table->date('task_date');
            $table->tinyInteger('task_status');
            $table->integer('employee_fee');
            $table->integer('employee_fee_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_order_tasks');
    }
};
