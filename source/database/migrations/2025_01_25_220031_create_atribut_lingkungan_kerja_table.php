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
        Schema::create('atribut_lingkungan_kerja', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_atribut_lk');
            $table->integer('status')->default(1)->comment('1 Aktif ; 0 Tidak Aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_lingkungan_kerja');
    }
};
