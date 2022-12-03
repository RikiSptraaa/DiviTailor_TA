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
        Schema::table('group_orders', function (Blueprint $table) {
            $table->bigInteger('price_per_item')->after('price');
            $table->integer('users_total')->after('order_kind');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_orders', function (Blueprint $table) {
            $table->dropColumn(['price_per_item', 'users_total']);
        });
    }
};
