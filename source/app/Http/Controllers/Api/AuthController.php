<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Cookie,Validator};
use App\Models\{User};
use App\Helpers\{ResponseHelper,GlobalHelper};
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $credentials = $req->only('username', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return ResponseHelper::data_not_found(__('auth.eds_invalid_credentials'));
            }
            $user = auth()->user();
            $dynamicAttributes = [
                'user_information' => $user,
                'token_information' => $this->respondWithToken($token),
            ];
            $cookie1 = Cookie::make('HashCookieUUID', base64_encode(Hash::make($token)), 60 * 24, '/', 'dev.erayadigital.co.id', true, true);
            $cookie2 = Cookie::make('CookieID', "OKE", 60 * 24, '/', 'dev.erayadigital.co.id', true, true);
            $response = ResponseHelper::success(__('auth.ino_login_success', ['username' => $req->input('username')]), $dynamicAttributes);
            $response->withCookie($cookie1)->withCookie($cookie2);
            return $response;
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function logout(Request $req)
    {
        auth('api')->logout();
        $cookie = Cookie::forget('jwt_token');
        return ResponseHelper::success(__('auth.eds_logout_success', ['username' => $req->input('username')]))->withCookie($cookie);
    }
    public function refreshToken()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}