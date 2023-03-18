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
        Schema::table('size_bajus', function (Blueprint $table) {
            $table->tinyInteger('jenis_ukuran')->after('lingkar_lengan_atas')->nullable();
            $table->char('kode_ukuran', 5)->after('jenis_ukuran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('size_bajus', function (Blueprint $table) {
            $table->dropIfExists('jenis_ukuran');
            $table->dropIfExists('kode_ukuran');
            //
        });
    }
};
