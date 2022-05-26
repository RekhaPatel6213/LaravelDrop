<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;
use Illuminate\Support\Facades\Auth;

class DropHeaders extends InitializeTenancyByRequestData
{
    /** @var string|null */
    public static $header = 'X-Dispensary';

    /** @var string|null */
    public static $queryParameter = 'dispensary';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if ($request->hasHeader(static::$header) && $request->header(static::$header) !== null) {
            foreach (array_keys(config('auth.guards')) as $guard) {
                if (Auth::guard($guard)->check()) {
                    if ($guard === config('app.dispensary_guard')) {
                        $dispensaryId = Auth::guard(config('app.dispensary_guard'))->user()->dispensary_id; // get dispensary id
                        if ((int) $dispensaryId === (int) $request->header(static::$header)) {
                            return $this->initializeTenancy($request, $next, $this->getPayload($request));
                        }
                    }

                    if ($guard === config('app.admin_guard')) {
                        return $this->initializeTenancy($request, $next, $this->getPayload($request));
                    }
                }
            }
            if (tenant('id') === null) {
                return response(['message' => __('message.set_x_dispensary_header')], 406);
            }
            return $next($request);
        }

        return response(['message' => __('message.set_x_dispensary_header')], 406);
    }
}
