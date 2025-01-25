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
        Schema::table('mcu_pf_gigi_lokasi', function (Blueprint $table) {
            $table->foreign(['user_id', 'transaksi_id'], 'HAPUS_GIGI_LOKASI')->references(['user_id', 'transaksi_id'])->on('mcu_pf_gigi')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcu_pf_gigi_lokasi', function (Blueprint $table) {
            $table->dropForeign('HAPUS_GIGI_LOKASI');
        });
    }
};
