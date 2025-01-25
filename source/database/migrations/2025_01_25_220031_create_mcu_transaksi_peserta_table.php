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
        Schema::create('mcu_transaksi_peserta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('no_transaksi');
            $table->dateTime('tanggal_transaksi');
            $table->integer('user_id');
            $table->integer('perusahaan_id');
            $table->integer('departemen_id');
            $table->text('proses_kerja');
            $table->integer('id_paket_mcu');
            $table->integer('petugas_id');
            $table->enum('jenis_transaksi_pendaftaran', ['MCU', 'Follow_Up', 'Berobat', 'MCU_Tambahan', 'MCU_Threadmill', 'Threadmill'])->default('MCU');
            $table->enum('status_peserta', ['selesai', 'proses', 'dibatalkan'])->nullable()->default('proses');
            $table->timestamps();

            $table->primary(['id', 'no_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_transaksi_peserta');
    }
};
