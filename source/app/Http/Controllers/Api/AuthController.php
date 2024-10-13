<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Cookie};
use App\Models\{User};
use App\Helpers\{ResponseHelper,GlobalHelper};


class AuthController extends Controller
{
    public function login(Request $req){
        try {
            $credentials = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            if (!$token = auth('api')->attempt($credentials)) {
                return ResponseHelper::data_not_found(__('auth.eds_invalid_credentials'));
            }
            $user = auth('api')->user();
            $dynamicAttributes = [
                'user_information' => $user,
                'token_information' => $this->respondWithToken($token),
            ];
            return ResponseHelper::success(__('auth.eds_login_success', ['username' => $req->input('username')]), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function logout(Request $req)
    {
        auth()->logout();
        return ResponseHelper::success(__('auth.eds_logout_success', ['username' => $req->input('username')]), $dynamicAttributes);
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