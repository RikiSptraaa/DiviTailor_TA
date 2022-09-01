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
        Schema::create('size_bajus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('panjang_baju')->nullable();
            $table->integer('lingkar_kerah')->nullable();
            $table->integer('lingkar_dada')->nullable();
            $table->integer('lingkar_perut')->nullable();
            $table->integer('lingkar_pinggul')->nullable();
            $table->integer('lebar_bahu')->nullable();
            $table->integer('panjang_lengan_pendek')->nullable();
            $table->integer('panjang_lengan_panjang')->nullable();
            $table->integer('lingkar_lengan_bawah')->nullable();
            $table->integer('lingkar_lengan_atas')->nullable();
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
        Schema::dropIfExists('size_bajus');
    }
};
