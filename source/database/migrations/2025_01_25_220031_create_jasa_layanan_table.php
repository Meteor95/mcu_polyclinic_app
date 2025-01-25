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
        Schema::create('jasa_layanan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode_jasa_pelayanan');
            $table->string('nama_jasa_pelayanan');
            $table->double('nominal_layanan');
            $table->enum('kategori_layanan', ['mcu', 'laboratorium', 'non_laboratorium'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasa_layanan');
    }
};
