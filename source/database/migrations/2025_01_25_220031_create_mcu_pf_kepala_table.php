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
        Schema::create('mcu_pf_kepala', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('transaksi_id')->nullable();
            $table->integer('id_atribut')->nullable();
            $table->string('nama_atribut')->nullable();
            $table->string('kategori_atribut')->nullable();
            $table->string('jenis_atribut')->nullable();
            $table->enum('status_atribut', ['abnormal', 'normal'])->nullable()->default('normal');
            $table->text('keterangan_atribut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_pf_kepala');
    }
};
