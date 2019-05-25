<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use  Illuminate\Support\Facades\Crypt;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('ADMIN')) {
           define('ADMIN', config('variables.APP_ADMIN', 'admin'));
        }
        require_once base_path('resources/macros/form.php');
        Schema::defaultStringLength(191);
        // Page Onload data
            // Data will only be loaded to declared views on $blade variable
            $blade = ['admin.partials.menu',
                'admin.partials.topbar',
                'admin.default',
                'admin.dashboard.*',  // dashboard
                'admin.schedule.*', // agent_schedule
                'admin.report.*', // work_reports
                'admin.incident_report.*', // incident_reports
                'admin.event_request.*', // leave_requests
                'admin.action_log.*', // action_logs
                'admin.hierarchy.*' // hierarchy
            ]; 
            // Onload Data: user's id and access_id
            \View::composer($blade,function($view){
                    //topbar, sidenav,
                $id = auth()->user()->id;
                $access_level = auth()->user()->access_id;
                $user = \App\Data\Models\UserInfo::find($id);
                $crypt_id = Crypt::encrypt($user->id);
                $view->with('pageOnload',$user)->with('access_id',$access_level)->with('crypt_id',$crypt_id);
            });
        //end page data onload
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}