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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('no_mcu')->nullable();
            $table->string('no_nota')->nullable()->index('no_nota');
            $table->dateTime('waktu_trx')->nullable();
            $table->dateTime('waktu_trx_sample')->nullable();
            $table->integer('id_dokter')->nullable();
            $table->string('nama_dokter')->nullable();
            $table->integer('id_pj')->nullable();
            $table->string('nama_pj')->nullable();
            $table->double('total_bayar')->nullable();
            $table->double('total_transaksi')->nullable();
            $table->integer('total_tindakan')->nullable();
            $table->integer('jenis_transaksi')->nullable()->comment('0 : Invoice
1 : Tunai
2 : Non Tunai');
            $table->string('metode_pembayaran')->nullable();
            $table->integer('id_kasir')->nullable();
            $table->enum('status_pembayaran', ['pending', 'process', 'done'])->nullable();
            $table->string('jenis_layanan')->nullable();
            $table->text('nama_file_surat_pengantar')->nullable();
            $table->integer('is_paket_mcu')->nullable();
            $table->string('nama_paket_mcu')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
