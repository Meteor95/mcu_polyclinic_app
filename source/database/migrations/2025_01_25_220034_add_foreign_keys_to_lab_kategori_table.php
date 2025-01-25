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
        Schema::table('lab_kategori', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'eds_lab_kategori_ibfk_1')->references(['id'])->on('lab_kategori')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_kategori', function (Blueprint $table) {
            $table->dropForeign('eds_lab_kategori_ibfk_1');
        });
    }
};
