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
        Schema::create('atribut_tingkat_kesadaran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('jenis_tingkat_kesadaran', ['keadaan_umum', 'status_kesadaran']);
            $table->string('nama_tingkat_kesadaran');
            $table->text('keterangan_tingkat_kesadaran')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_tingkat_kesadaran');
    }
};
