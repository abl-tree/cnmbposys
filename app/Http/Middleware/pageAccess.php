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
        $position="";
        $pageAccess['hr'] = ['/','dashboard']; 
        $pageAccess['rta'] = ['/','dashboard','schedule']; 
        $pageAccess['admin'] = ['/','dashboard','rtadashboard','rtaschedule',"rtareport","tldashboard","tlreport",'incident_report','rtaeventrequest'];
        $pageAccess['tl'] = ['/',"tldashboard","tlreport"];
        // if($request->user()->access->id < 15 && $request->user()->access->id > 11){
        //     // if(in_array($route,$pageAccess['rta'])){
        //     //     return redirect($route);
        //     // }
        // }else{
        //     return back();
        // }

        switch(\Auth::user()->access->id){
            case 1:
                $position = 'admin';
            break;
            case 2:case 3:
                $position = 'hr';
            break;
            case 12:case 13:case 14:
                $position = 'rta';
            break;
            
            case 16:
                $position = 'tl';
            break;
        }

        $tmp = $this->pass($uri,$pageAccess[$position]);
        if($tmp){
            return $next($request);
        }else{
            return abort(404);
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