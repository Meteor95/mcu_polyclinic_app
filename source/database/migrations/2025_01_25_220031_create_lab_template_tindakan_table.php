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
        Schema::create('lab_template_tindakan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('used_paket_mcu')->nullable();
            $table->integer('id_paket_mcu')->nullable();
            $table->string('nama_template')->nullable();
            $table->json('meta_data_template')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_template_tindakan');
    }
};
