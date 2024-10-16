<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PegawaiTokenSqlite extends Model
{
    use HasFactory;
    protected $table = 'tms_tmp_token';
    protected $fillable = [
        'id_user',
        'username',
        'email',
        'token',
    ];
    public static function add_temp_token($token,$user){
        DB::connection('sqlite')->table('tms_tmp_token')
        ->updateOrInsert(
            ['id_user' => $user->id],
            ['username' => $user->username,'email' => $user->email, 'token' => $token]
        );
    }
    public static function check_user_ready($user){
        return DB::connection('sqlite')->table('tms_tmp_token')
        ->where('id_user', $user)
        ->orWhere('username', $user)
        ->value('token');
    }
    
}
