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
        Schema::create('transaksi_kesimpulan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_mcu');
            $table->text('kesimpulan_pemeriksaan_fisik')->nullable();
            $table->string('status_pemeriksaan_laboratorium');
            $table->text('kesimpulan_pemeriksaan_laboratorum')->nullable();
            $table->text('kesimpulan_pemeriksaan_threadmill')->nullable();
            $table->text('kesimpulan_pemeriksaan_ronsen')->nullable();
            $table->text('kesimpulan_pemeriksaan_ekg')->nullable();
            $table->text('kesimpulan_pemeriksaan_audio_kiri')->nullable();
            $table->text('kesimpulan_pemeriksaan_audio_kanan')->nullable();
            $table->text('kesimpulan_pemeriksaan_spiro_restriksi')->nullable();
            $table->text('kesimpulan_pemeriksaan_spiro_obstruksi')->nullable();
            $table->text('kesimpulan_keseluruhan')->nullable();
            $table->text('saran_keseluruhan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kesimpulan');
    }
};
