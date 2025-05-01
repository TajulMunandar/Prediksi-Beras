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
        Schema::create('prediksi_data_barus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beras_id');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->boolean('hari_besar');
            $table->float('curah_hujan');
            $table->float('suhu');
            $table->float('kelembaban');
            $table->float('harga_prediksi');
            $table->timestamp('tanggal_input')->useCurrent();
            $table->timestamps();
            $table->foreign('beras_id')->references('id')->on('beras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediksi_data_barus');
    }
};
