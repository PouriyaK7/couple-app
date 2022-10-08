<?php

namespace App\Http\Middleware;

use App\Services\CoupleService;
use Closure;
use Illuminate\Http\Request;

class CheckCoupleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # Return 403 status code if user does not have access to the couple
        if (!CoupleService::checkAccess(auth()->id(), $request->route('id'))) {
            abort(403);
        }
        return $next($request);
    }
}
