<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserExtract
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()) {
            $jwt = app('tymon.jwt');
            try {
                if ($jwt->getToken()) {
                    $jwt->getPayload();
                    return app(Authenticate::class)->handle($request, $next, 'api');
                }
            } catch (Exception $ex) {
                // Something went wrong while trying to get user
                // dd($ex);
            }
        }
        return $next($request);
    }
}
