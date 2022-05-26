<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Crypt;
use App\Http\Traits\AuthTrait;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    use AuthTrait;

    protected $guardName;

    public function __construct()
    {
        $this->guardName = config('app.admin_guard');
        if(Route::currentRouteName() === 'dispensary.login'){
            $this->guardName = config('app.dispensary_guard');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!$token = auth($this->guardName)->attempt($request->only(['email', 'password']))) {
                return response()->json(['message' => 'Password is invalid. Please provide correct password.'], 401);
            }
        } catch (DecryptException $e) {
            return response()->json(['message' => 'Please provide valid credential.'], 401);
        }

        $this->updateLastLogin($this->guardName);
        return $this->generateTokenResponse($this->guardName, $token);
    }

    public function logout()
    {
        if (auth($this->guardName)->check()) {
            auth($this->guardName)->logout();
        }
        return response()->json(['message' => 'User signed out successfully ']);
    }
}
