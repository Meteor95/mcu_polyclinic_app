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
        Schema::create('users_member_temp', function (Blueprint $table) {
            $table->string('uuid');
            $table->string('nomor_identitas');
            $table->string('nama_peserta');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('tipe_identitas', ['KTP', 'SIM', 'Paspor', 'Visa'])->default('KTP');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan', 'Alien']);
            $table->text('alamat')->nullable();
            $table->enum('status_kawin', ['Menikah', 'Belum Menikah', 'Cerai Hidup', 'Cerai Mati']);
            $table->string('no_telepon');
            $table->string('email')->nullable();
            $table->timestamps();

            $table->primary(['nomor_identitas', 'uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_member_temp');
    }
};
