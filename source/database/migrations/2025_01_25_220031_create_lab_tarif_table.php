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
        Schema::create('lab_tarif', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode_item')->nullable();
            $table->string('nama_item')->nullable();
            $table->string('group_item')->nullable();
            $table->integer('id_kategori')->nullable();
            $table->string('nama_kategori')->nullable();
            $table->string('satuan')->nullable();
            $table->string('jenis_item')->nullable();
            $table->json('meta_data_kuantitatif')->nullable();
            $table->json('meta_data_kualitatif')->nullable();
            $table->double('harga_dasar')->nullable();
            $table->json('meta_data_jasa')->nullable();
            $table->double('harga_jual')->nullable();
            $table->enum('visible_item', ['rahasia', 'sembunyikan', 'tampilkan'])->nullable()->default('sembunyikan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tarif');
    }
};
