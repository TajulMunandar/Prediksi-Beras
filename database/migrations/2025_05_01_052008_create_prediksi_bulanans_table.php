<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prediksi_bulanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beras_id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->float('harga_prediksi');
            $table->float('harga_aktual')->nullable();
            $table->timestamps();
            $table->foreign('beras_id')->references('id')->on('beras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediksi_bulanans');
    }
};
