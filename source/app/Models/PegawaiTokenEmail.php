<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PegawaiTokenEmail extends Model
{
    use HasFactory;
    protected $table = 'tms_tmp_token_email';
    protected $fillable = [
        'email',
        'token',
    ];
    public static function add_temp_token_mail($req,$token_reset){
        DB::connection('sqlite')->table('tms_tmp_token_email')
        ->updateOrInsert(
            ['email' => $req->email],
            ['token' => $token_reset]
        );
    }
    public static function check_user_ready_mail($req){
        return DB::connection('sqlite')->table('tms_tmp_token_email')
        ->where('email', $req->query('email'))
        ->Where('token', $req->query('token'))
        ->value('token');
    }
    public static function delete_token_reset($email){
        return DB::connection('sqlite')->table('tms_tmp_token_email')
        ->where('email', $email)
        ->delete();
    }
}
