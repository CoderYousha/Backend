<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationAuthentication
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
        if (Auth::guard('patient')->user() && Auth::guard('patient')->user()->status === 'activated') {
            return $next($request);
        } else if ((Auth::guard('user')->user()->account_type === 'reception' || Auth::guard('user')->user()->account_type === 'doctor') && Auth::guard('user')->user()->status === 'activated') {
            return $next($request);
        }
        return error('some thing went wrong', "you don't have authentication", 502);
    }
}
