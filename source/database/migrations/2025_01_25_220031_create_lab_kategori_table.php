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
        Schema::create('lab_kategori', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_kategori');
            $table->integer('parent_id')->nullable()->index('parent_id');
            $table->enum('grup_kategori', ['laboratorium', 'non_laboratorium'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_kategori');
    }
};
