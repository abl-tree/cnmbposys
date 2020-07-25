<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App\Data\Models\RouteList;

class Permission
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
        $response = $next($request);
        $currentRoute = Route::current();
        $uri = $currentRoute->uri;
        $method = $currentRoute->methods ? $currentRoute->methods[0] : '';
        $user_role = $request->user()->access->id;
        $route = RouteList::where('uri', $uri)->where('method', 'LIKE', '%'.$method.'%')->first();
        $permission = $route->permissions()->where(['access_id' => $user_role, 'allowed' => true])->first();

        if(!$permission)
        return response([
            'code' => 401,
            'title' => 'Unauthorized access',
            'description' => 'You do not have permission to access this endpoint.'
        ], 401);

        return $response;
    }
}
