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
        Schema::create('mcu_pf_gigi_lokasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('transaksi_id')->nullable();
            $table->string('atas_kanan_8')->nullable();
            $table->string('atas_kanan_7')->nullable();
            $table->string('atas_kanan_6')->nullable();
            $table->string('atas_kanan_5')->nullable();
            $table->string('atas_kanan_4')->nullable();
            $table->string('atas_kanan_3')->nullable();
            $table->string('atas_kanan_2')->nullable();
            $table->string('atas_kanan_1')->nullable();
            $table->string('atas_kiri_1')->nullable();
            $table->string('atas_kiri_2')->nullable();
            $table->string('atas_kiri_3')->nullable();
            $table->string('atas_kiri_4')->nullable();
            $table->string('atas_kiri_5')->nullable();
            $table->string('atas_kiri_6')->nullable();
            $table->string('atas_kiri_7')->nullable();
            $table->string('atas_kiri_8')->nullable();
            $table->string('bawah_kanan_8')->nullable();
            $table->string('bawah_kanan_7')->nullable();
            $table->string('bawah_kanan_6')->nullable();
            $table->string('bawah_kanan_5')->nullable();
            $table->string('bawah_kanan_4')->nullable();
            $table->string('bawah_kanan_3')->nullable();
            $table->string('bawah_kanan_2')->nullable();
            $table->string('bawah_kanan_1')->nullable();
            $table->string('bawah_kiri_1')->nullable();
            $table->string('bawah_kiri_2')->nullable();
            $table->string('bawah_kiri_3')->nullable();
            $table->string('bawah_kiri_4')->nullable();
            $table->string('bawah_kiri_5')->nullable();
            $table->string('bawah_kiri_6')->nullable();
            $table->string('bawah_kiri_7')->nullable();
            $table->string('bawah_kiri_8')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['user_id', 'transaksi_id'], 'hapus_gigi_lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_pf_gigi_lokasi');
    }
};
