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
        Schema::create('mcu_pf_penglihatan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('transaksi_id');
            $table->string('visus_os_tanpa_kacamata_jauh')->nullable();
            $table->string('visus_od_tanpa_kacamata_jauh')->nullable();
            $table->string('visus_os_kacamata_jauh')->nullable();
            $table->string('visus_od_kacamata_jauh')->nullable();
            $table->string('visus_os_tanpa_kacamata_dekat')->nullable();
            $table->string('visus_od_tanpa_kacamata_dekat')->nullable();
            $table->string('visus_os_kacamata_dekat')->nullable();
            $table->string('visus_od_kacamata_dekat')->nullable();
            $table->string('buta_warna')->nullable();
            $table->string('lapang_pandang_superior_os')->nullable();
            $table->string('lapang_pandang_inferior_os')->nullable();
            $table->string('lapang_pandang_temporal_os')->nullable();
            $table->string('lapang_pandang_nasal_os')->nullable();
            $table->text('lapang_pandang_keterangan_os')->nullable();
            $table->string('lapang_pandang_superior_od')->nullable();
            $table->string('lapang_pandang_inferior_od')->nullable();
            $table->string('lapang_pandang_temporal_od')->nullable();
            $table->string('lapang_pandang_nasal_od')->nullable();
            $table->text('lapang_pandang_keterangan_od')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_pf_penglihatan');
    }
};
