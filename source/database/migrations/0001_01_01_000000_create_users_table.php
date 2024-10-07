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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', length: 36)->index();
            $table->string('username')->unique()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->bigInteger('idhakakses')->nullable();
            $table->timestamps();
        });
        Schema::create('users_pegawai', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->bigInteger('id_user')->index();
            $table->string('nik');
            $table->string('nip');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan', 'Alien']);
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->integer('status_visible')->default('1');
            $table->timestamps();
        });
        Schema::create('users_perusahaan', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->bigInteger('id_perusahaan')->index();
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('no_telepon');
            $table->timestamps();
        });
        Schema::create('users_peserta', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->bigInteger('id_user')->index();
            $table->integer('id_perusahaan')->default('0');
            $table->string('nik');
            $table->string('nama_peserta');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan', 'Alien']);
            $table->string('jabatan');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        if (!Schema::connection('sqlite')->hasTable('tms_tmp_token')) {
            Schema::connection('sqlite')->create('tms_tmp_token', function (Blueprint $table) {
                $table->id();
                $table->string('id_user');
                $table->string('username');
                $table->string('email');
                $table->string('token');
            });
        }
        if (!Schema::connection('sqlite')->hasTable('tms_tmp_token_email')) {
            Schema::connection('sqlite')->create('tms_tmp_token_email', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->string('token');
            });
        }
        Schema::create('users_hakakses', function (Blueprint $table) {
            $table->id()->primary()->index();
            $table->text('nama_hak_akses');
            $table->text('hakakses_json');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('users_hakakses');
        Schema::connection('sqlite')->dropIfExists('tms_tmp_token_email');
        Schema::connection('sqlite')->dropIfExists('tms_tmp_token');
    }
};
