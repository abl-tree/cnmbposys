<?php

use Illuminate\Database\Seeder;
use App\Data\Models\RouteList;
use App\Data\Models\AccessLevel;
use App\Data\Models\Permission;
use Illuminate\Support\Facades\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routeCollection = Route::getRoutes();
        $roles = AccessLevel::get();

        foreach ($routeCollection as $route) {
            if(isset($route->action['middleware']) && is_array($route->action['middleware']) && in_array('api', $route->action['middleware'])) {
                $routelist = RouteList::create([
                    'method' => implode(",", $route->methods),
                    'uri' => $route->uri
                ]);
    
                if($routelist) {
                    foreach ($roles as $key => $role) {
                        Permission::create([
                            'route_id' => $routelist->id,
                            'access_id' => $role->id
                        ]);
                    }
                }
            }
        }
    }
}
