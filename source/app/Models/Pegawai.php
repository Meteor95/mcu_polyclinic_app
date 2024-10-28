<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'users_pegawai';
    protected $fillable = [
        'id',
        'nama_pegawai',
        'nip',
        'jabatan',
        'departemen',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'tanggal_bergabung',
        'status_pegawai',
    ];
}
