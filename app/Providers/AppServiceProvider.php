<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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



        //page data onload


        \View::composer('admin.dashboard.rta',function($view){
                //topbar, sidenav,
            $id = auth()->user()->id;
            $access_level = auth()->user()->access_id;
            $user = \App\Data\Models\UserInfo::find($id);
            $view->with('pageOnload',$user);
            // ->with('menu_items', $menu);
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
