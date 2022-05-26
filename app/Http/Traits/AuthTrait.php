<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait AuthTrait
{
    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
    */
    private function generateTokenResponse($guardName, $token)
    {
        $authUser = Auth::guard($guardName)->user();
        $responseData = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard($guardName)->factory()->getTTL() * 60,
            'guard_name' => $guardName,
            'user_id' =>  $guardName === config('app.dispensary_guard') ? $authUser->dispensary->id : $authUser->id
        ];
        return response()->json($responseData);
    }

    private function updateLastLogin($guardName)
    {
        $user = Auth::guard($guardName)->user();
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
    }
}
