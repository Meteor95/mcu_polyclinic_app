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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_transaksi')->nullable()->index('trx_detail');
            $table->string('kode_item')->nullable();
            $table->string('nama_item')->nullable();
            $table->double('harga')->nullable();
            $table->double('diskon')->nullable();
            $table->double('harga_setelah_diskon')->nullable();
            $table->double('jumlah')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('meta_data_kuantitatif')->nullable();
            $table->json('meta_data_kualitatif')->nullable();
            $table->text('meta_data_jasa')->nullable();
            $table->text('meta_data_jasa_fee')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
