<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechincalAuthentication
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
        if (Auth::guard('user')->user()->account_type === 'doctor' && Auth::guard('user')->user()->medical_specialization === 'Technical' && Auth::guard('user')->user()->status === 'activated') {
            return $next($request);
        }
        return error('some thing went wrong', "you don't have authentication", 502);
    }
}