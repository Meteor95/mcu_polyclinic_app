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
        Schema::create('mcu_poli_citra', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_trx_poli');
            $table->enum('jenis_poli', ['poli_spirometri', 'poli_audiometri', 'poli_ekg', 'poli_threadmill', 'poli_ronsen']);
            $table->text('nama_file_asli');
            $table->string('nama_file');
            $table->text('meta_citra')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_poli_citra');
    }
};
