<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class loginVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        if (Auth::check() && Auth::user()->loginFlag == true) {
            return $next($request);
        }
        return redirect('/security');
    }
}
