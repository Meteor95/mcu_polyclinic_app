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
        Schema::create('mcu_lingkungan_kerja_peserta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('transaksi_id');
            $table->string('id_atribut_lk');
            $table->string('nama_atribut_saat_ini')->nullable();
            $table->integer('status')->nullable()->comment('1: Ya 0 Tidak');
            $table->double('nilai_jam_per_hari');
            $table->double('nilai_selama_x_tahun');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_lingkungan_kerja_peserta');
    }
};
