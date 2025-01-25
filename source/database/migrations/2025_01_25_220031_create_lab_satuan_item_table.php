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
        Schema::create('lab_satuan_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_satuan')->nullable();
            $table->enum('grup_satuan', ['laboratorium', 'non_laboratorium'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_satuan_item');
    }
};
