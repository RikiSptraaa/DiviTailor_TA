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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('tanggal_estimasi')->after('order_date');
            $table->tinyInteger('jenis_pakaian')->after('tanggal_estimasi');
            $table->string('jenis_pembuatan', 50)->after('jenis_pakaian');
            $table->tinyInteger('jenis_kain')->comment('Jenis Kain 1.Wool 2.Cotton 3.Linen 4.Jean 5.High Twist')->after('jenis_pembuatan');
            $table->boolean('jenis_panjang')->comment('jenis lengan 1.Lengan Panjang 2.Lengan Pendek 3.Celana Panjang 4.Celana Pendek')->after('jenis_kain');
            $table->text('deskripsi_pakaian')->after('jenis_panjang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tanggal_estimasi');
            $table->dropColumn('jenis_pakaian');
            $table->dropColumn('jenis_pembuatan');
            $table->dropColumn('jenis_kain');
            $table->dropColumn('jenis_panjang');
            $table->dropColumn('deskripsi_pakaian');
        });
    }
};
