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
        Schema::create('atribut_poli_kesimpulan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('jenis_poli', ['poli_spirometri', 'poli_audiometri', 'poli_ekg', 'poli_threadmill', 'poli_ronsen']);
            $table->text('keterangan_kesimpulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_poli_kesimpulan');
    }
};
