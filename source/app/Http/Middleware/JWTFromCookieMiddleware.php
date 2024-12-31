<?php
 
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\{Cookie, Session, Log};
use App\Models\User;

class JWTFromCookieMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = Cookie::get('token_device');
        $user_id = Cookie::get('user_id');
        if (!$token) {
            return redirect()->route('login');
        }
        try {
            $userDetails = Session::get('user_details_' . $user_id);
            $userPermissions = Session::get('user_permissions_' . $user_id);
            $user_id = Session::get('user_id');
            if (!$userDetails) {
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();
                $user_id = $user->id;
                $userDetails = $user->detailUserInformation($user_id);
                Session::put('user_id', $user_id);
                Session::put('user_details_' . $user_id, $userDetails);
                Session::put('token_device_' . $user_id, $token);
                Session::put('user_permissions_' . $user_id, $user->getPermissionsViaRoles());
            }
            $request->attributes->set('user_id', $user_id);
            $request->attributes->set('user_details', $userDetails);
            $request->attributes->set('user_permissions', $userPermissions);
        } catch (JWTException $e) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
