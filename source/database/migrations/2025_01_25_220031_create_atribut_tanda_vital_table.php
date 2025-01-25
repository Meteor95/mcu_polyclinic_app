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
        Schema::create('atribut_tanda_vital', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('jenis_tanda_vital');
            $table->string('nama_atribut_tv');
            $table->string('satuan', 100);
            $table->text('keterangan')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_tanda_vital');
    }
};
