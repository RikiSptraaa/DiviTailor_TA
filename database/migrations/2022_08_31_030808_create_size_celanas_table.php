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
        Schema::create('size_celanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('lingkar_pinggang')->nullable();
            $table->integer('lingkar_pinggul')->nullable();
            $table->integer('panjang_celana')->nullable();
            $table->integer('panjang_pesak')->nullable();
            $table->integer('lingkar_bawah')->nullable();
            $table->integer('lingkar_paha')->nullable();
            $table->integer('lingkar_lutut')->nullable();
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
        Schema::dropIfExists('size_celanas');
    }
};
