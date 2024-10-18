<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $req)
    {
       $cookie_jwt = Cookie::make('jwt_token', "PIGYMARU", (60 * 24), '/', null, true, true);
       return response()->json(['message' => 'Login successful'])->withCookie($cookie_jwt);
    }
    public function logout(Request $req)
    {
       $jwt_token = $req->cookie('jwt_token');
       echo $jwt_token;
    }
    
}
