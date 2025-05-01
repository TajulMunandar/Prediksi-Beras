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
        Schema::create('evaluasi_models', function (Blueprint $table) {
            $table->id();
            $table->float('mae');
            $table->float('mse');
            $table->float('rmse');
            $table->float('r2_score');
            $table->float('persentase_error');
            $table->float('akurasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_models');
    }
};
