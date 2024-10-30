<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\{User, Pegawai};
use Spatie\Permission\Models\Role;

use Exception;

class UserServices
{
    /**
     * Handle database transaction.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function handleTransactionRegisterUser($data)
    {
        return DB::transaction(function () use ($data) {
            $users = [
                'uuid' => (string) Str::uuid(),
                'username' => $data['username'] ?? '',
                'email' => $data['email'] ?? '',
                'email_verified_at' => now(),
                'password' => Hash::make($data['password']),
            ];
            User::create($users);
            $userdata = User::where('username', $data['username'])->first();
            User::assignRole($data['idhakakses'], $userdata->id);
            $employed = [
                'id' => $userdata->id,
                'nama_pegawai' => $data['nama_pegawai'] ?? '',
                'nip' => $data['nip'] ?? '',
                'jabatan' => $data['jabatan'] ?? '',
                'departemen' => $data['departemen'] ?? '',
                'tanggal_lahir' => (!empty($data['tanggal_lahir']) ? Carbon::createFromFormat('d-m-Y', $data['tanggal_lahir'])->format('Y-m-d') : null),
                'jenis_kelamin' => $data['jenis_kelamin'] ?? 'Laki-Laki',
                'alamat' => $data['alamat'] ?? '',
                'no_telepon' => $data['no_telepon'] ?? '',
                'tanggal_bergabung' => (!empty($data['tanggal_diterima']) ? Carbon::createFromFormat('d-m-Y', $data['tanggal_diterima'])->format('Y-m-d') : null),
                'status_pegawai' => $data['status_pegawai'] ?? '',
            ];              
            Pegawai::create($employed);
        });
    }
    public function handleTransactionDeleteUser($data){
        return DB::transaction(function () use ($data) {
            User::where('id', '=', $data['id'])->delete();
            Pegawai::where('id', '=', $data['id'])->delete();
            User::deleteRole($data['id']);
        });
    }
    public function handleTransactionEditUser($data){
        return DB::transaction(function () use ($data) {
            $datauser = [
                'username' => $data['username'] ?? '',
                'email' => $data['email'] ?? '',
            ];
            if (!empty($data['password'])) {
                $datauser['password'] = Hash::make($data['password']);
            }
            User::where('id', '=', $data['id_pengguna'])->update($datauser);
            $datapegawai = [
                'nama_pegawai' => $data['nama_pegawai'] ?? '',
                'nip' => $data['nip'] ?? '',
                'jabatan' => $data['jabatan'] ?? '',
                'departemen' => $data['departemen'] ?? '',
                'tanggal_lahir' => (!empty($data['tanggal_lahir']) ? Carbon::createFromFormat('d-m-Y', $data['tanggal_lahir'])->format('Y-m-d') : null),
                'jenis_kelamin' => $data['jenis_kelamin'] ?? 'Laki-Laki',
                'alamat' => $data['alamat'] ?? '',
                'no_telepon' => $data['no_telepon'] ?? '',
                'tanggal_bergabung' => (!empty($data['tanggal_diterima']) ? Carbon::createFromFormat('d-m-Y', $data['tanggal_diterima'])->format('Y-m-d') : null),
                'status_pegawai' => $data['status_pegawai'] ?? '',
            ];
            Pegawai::where('id', '=', $data['id_pengguna'])->update($datapegawai);
            User::assignRole($data['idhakakses'], $data['id_pengguna']);
        });
    }
}
