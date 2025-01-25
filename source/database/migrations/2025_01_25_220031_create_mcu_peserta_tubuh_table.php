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
        Schema::create('mcu_peserta_tubuh', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('sampling_darah_golongan')->nullable();
            $table->dateTime('sampling_darah_waktu', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcu_peserta_tubuh');
    }
};
