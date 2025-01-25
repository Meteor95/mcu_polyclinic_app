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
        Schema::create('mcu_peserta_gambar', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('transaksi_id');
            $table->text('lokasi_gambar')->nullable();
            $table->timestamps();

            $table->primary(['id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_peserta_gambar');
    }
};
