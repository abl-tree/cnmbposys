<?php

namespace App\Http\Middleware;
use Closure;

class pageAccess
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
        $uri = $request->path();
        $pageAccess['hr'] = ['/','dashboard']; 
        $pageAccess['rta'] = ['/','dashboard','schedule']; 

        // if($request->user()->access->id < 15 && $request->user()->access->id > 11){
        //     // if(in_array($route,$pageAccess['rta'])){
        //     //     return redirect($route);
        //     // }
        // }else{
        //     return back();
        // }


        switch(\Auth::user()->access->id){
            case 1:case 2:case 3:
                    $tmp = $this->pass($uri,$pageAccess['hr']);
                    if($tmp){
                        return $next($request);
                    }else{
                        return abort(404);
                    }
                break;
            case 12:case 13:case 14:
                    $tmp = $this->pass($uri,$pageAccess['rta']);
                    if($tmp){
                        return $next($request);
                    }else{
                        return back();
                    }
                break;
        }

        // return $next($request);
    }

    public function pass($uri,$arr){
        if(in_array($uri,$arr)){
            return true;
        }else{
            return false;
        }
    }
}
