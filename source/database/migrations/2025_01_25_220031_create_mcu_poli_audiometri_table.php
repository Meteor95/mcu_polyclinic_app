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
        Schema::create('mcu_poli_audiometri', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pegawai_id');
            $table->string('user_id');
            $table->integer('transaksi_id');
            $table->string('judul_laporan');
            $table->integer('id_kesimpulan')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('detail_kesimpulan')->nullable();
            $table->text('catatan_kaki')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_poli_audiometri');
    }
};
