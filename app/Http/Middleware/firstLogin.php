<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class firstLogin
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
        if (Auth::check() && Auth::user()->loginFlag == false) {
            return $next($request);
        }
        if (Auth::check() && Auth::user()->loginFlag == true){
            return redirect('/dashboard');
        }
    }
}
