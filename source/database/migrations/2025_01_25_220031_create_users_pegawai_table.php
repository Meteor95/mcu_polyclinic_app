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
        Schema::create('users_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pegawai', 100);
            $table->string('nik', 50)->nullable()->unique('nip');
            $table->string('jabatan', 100)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_berhenti')->nullable();
            $table->enum('status_pegawai', ['Tetap', 'Kontrak', 'Magang', 'Tidak Aktif'])->nullable()->default('Tetap');
            $table->text('tanda_tangan_pegawai')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_pegawai');
    }
};
