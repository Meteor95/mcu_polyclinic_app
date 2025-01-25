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
        Schema::create('mcu_riwayat_penyakit_terdahulu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('transaksi_id');
            $table->integer('id_atribut_pt');
            $table->string('nama_atribut_saat_ini');
            $table->integer('status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_riwayat_penyakit_terdahulu');
    }
};
