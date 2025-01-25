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
        Schema::create('mcu_riwayat_kebiasaan_hidup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('transaksi_id');
            $table->integer('id_atribut_kb');
            $table->string('nama_kebiasaan');
            $table->integer('status_kebiasaan');
            $table->double('nilai_kebiasaan')->nullable();
            $table->dateTime('waktu_kebiasaan')->nullable();
            $table->string('satuan_kebiasaan');
            $table->integer('jenis_kebiasaan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_riwayat_kebiasaan_hidup');
    }
};
