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
        Schema::create('lab_kesimpulan_tindakan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('jenis_kesimpulan')->nullable();
            $table->text('keterangan_kesimpulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_kesimpulan_tindakan');
    }
};
