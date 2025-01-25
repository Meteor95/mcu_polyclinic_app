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
        Schema::create('transaksi_fee', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode_jasa')->nullable();
            $table->integer('id_transaksi')->nullable()->index('trx_jasa');
            $table->integer('id_petugas')->nullable();
            $table->string('nama_petugas')->nullable();
            $table->string('kode_item')->nullable();
            $table->string('nama_tindakan')->nullable();
            $table->double('nominal_fee')->nullable();
            $table->double('besaran_fee')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_fee');
    }
};
