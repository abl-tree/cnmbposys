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
        $pageAccess['hrm'] = ['/','dashboard','todays_activity',"incident_reports",'action_logs']; 
        $pageAccess['hra'] = ['/','dashboard',"incident_reports",'action_logs']; 
        $pageAccess['rtam'] = ['/','dashboard','todays_activity','agent_schedules',"incident_reports","work_reports","leave_requests",'action_logs']; 
        $pageAccess['rtas'] = ['/','dashboard','todays_activity','agent_schedules',"incident_reports","work_reports","leave_requests",'action_logs']; 
        $pageAccess['rtaa'] = ['/','dashboard','agent_schedules',"incident_reports","work_reports","leave_requests",'action_logs']; 
        $pageAccess['admin'] = ['/','dashboard','todays_activity','agent_schedules',"incident_reports","work_reports","leave_requests",'action_logs','agent'];
        $pageAccess['om'] = ['/','dashboard','todays_activity',"incident_reports"];
        $pageAccess['tl'] = ['/','dashboard','todays_activity',"incident_reports"];
        $pageAccess['agent'] = ['/','work_logs',"incident_reports"];
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
            case 2:
                $position = 'hrm'; // hr manager
            break;
            case 3:
                $position = 'hra'; // hr assistant
            break;
            case 4:
                $position = 'itsu'; //it supervisor
            break;
            case 5:
                $position = 'itsp'; //it specialist
            break;
            case 6:
                $position = 'itsupport';
            break;
            case 12:
                $position = 'rtam';
            break;
            case 13:
                $position = 'rtas';
            break;
            case 14:
                $position = 'rtaa';
            break;
            case 16:
                $position = 'tl';
            break;
            case 17:
                $position = 'agent';
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