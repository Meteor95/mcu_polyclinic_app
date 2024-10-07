<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Cookie};
use App\Models\{User,PersonalAccessToken,PegawaiTokenSqlite};
use App\Helpers\{ResponseHelper,GlobalHelper};


class AuthController extends Controller
{
    public function login(Request $req){
        try {
            $tokenstring = __('data.eds_token_not_generate');
            $user = User::getUserWithHakAkses($req);
            if (!$user || !Hash::check($req->input('password'), $user->password)) return ResponseHelper::data_not_found(__('auth.ino_invalid_credentials'));
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if (!$token){
                $tokenstring = $user->createToken($req->input('username').'_API_TOKEN',['*'],Carbon::now()->addDays(6))->plainTextToken;
                PegawaiTokenSqlite::add_temp_token($tokenstring,$user);
            }else{
                $expiresAt = Carbon::parse($token->expires_at);
                if ($expiresAt->isPast()){
                    $token->delete();
                    $tokenstring = $user->createToken($req->input('username').'_API_TOKEN',['*'],Carbon::now()->addDays(6))->plainTextToken;
                    PegawaiTokenSqlite::add_temp_token($tokenstring,$user);
                }else{
                    $tokenstring = PegawaiTokenSqlite::check_user_ready($user->id);
                }
            }
            $dynamicAttributes = [
                'token' => $tokenstring,
                'fitur' => json_decode($user->hakakses_json, true),
            ];
            if ($req->input('access_form') === "web_login") {
                $cookie1 = Cookie::make('HashCookieUUID', base64_encode(Hash::make($tokenstring)), (60 * 24));
                $cookie2 = Cookie::make('CookieID', base64_encode($user->id), (60 * 24));
                $response = ResponseHelper::success(__('auth.ino_login_success', ['username' => $req->input('username')]), $dynamicAttributes);
                $response->withCookie($cookie1)->withCookie($cookie2);
                return $response;
            }
            return ResponseHelper::success(__('auth.ino_login_success', ['username' => $req->input('username')]), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}