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
        Schema::create('mcu_pf_tingkat_kesadaran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('transaksi_id');
            $table->integer('id_atribut_tingkat_kesadaran');
            $table->string('nama_atribut_tingkat_kesadaran');
            $table->string('keterangan_tingkat_kesadaran')->nullable();
            $table->integer('id_atribut_status_tingkat_kesadaran');
            $table->string('nama_atribut_status_tingkat_kesadaran');
            $table->text('keterangan_status_tingkat_kesadaran')->nullable();
            $table->text('keluhan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_pf_tingkat_kesadaran');
    }
};
