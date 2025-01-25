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
        Schema::create('atribut_kebiasaan_hidup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_atribut_kb');
            $table->string('nama_satuan_kb');
            $table->integer('status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_kebiasaan_hidup');
    }
};
