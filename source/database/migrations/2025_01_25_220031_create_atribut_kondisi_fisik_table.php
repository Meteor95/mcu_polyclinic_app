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
        Schema::create('atribut_kondisi_fisik', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_atribut_fisik');
            $table->string('kategori_lokasi_fisik')->nullable();
            $table->string('jenis_pemeriksaan');
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_kondisi_fisik');
    }
};
